<?php

namespace App\Containers\SharedSection\Room\Jobs;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomImageGroup;
use App\Ship\Parents\Jobs\Job as ParentJob;
use App\Ship\Services\ImageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;
use ZipArchive;

class ProcessRoomZipImageJob extends ParentJob implements ShouldQueue
{
    /**
     * Tăng số giây được phép chạy của Job trước khi bị tính là timeout.
     * 3600 = 1 giờ.
     */
    public $timeout = 3600;

    protected $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle(ImageService $svc)
    {
        if (!file_exists($this->filePath)) {
            Log::error("Job ProcessRoomZipImageJob: File không tồn tại - {$this->filePath}");
            return;
        }

        Log::info("Job ProcessRoomZipImageJob: Bắt đầu xử lý file {$this->filePath}");

        $zip = new ZipArchive();
        if ($zip->open($this->filePath) === true) {
            $extractPath = storage_path('app/temp_import_' . uniqid());
            if (!file_exists($extractPath)) {
                mkdir($extractPath, 0777, true);
            }

            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            Log::error("Job ProcessRoomZipImageJob: Không mở được file zip - {$this->filePath}");
            if (file_exists($this->filePath)) {
                unlink($this->filePath);
            }
            return;
        }

        // Xóa thư mục __MACOSX nếu có (thường là của macOS)
        $macosxPath = $extractPath . '/__MACOSX';
        if (file_exists($macosxPath)) {
            File::deleteDirectory($macosxPath);
        }

        // Lấy danh sách folder phòng (bỏ qua __MACOSX)
        $roomFolders = array_filter(glob($extractPath . '/*'), function ($dir) {
            return is_dir($dir) && basename($dir) !== '__MACOSX';
        });

        // Nếu chỉ có 1 thư mục cha, vào sâu thêm 1 cấp
        if (count($roomFolders) === 1) {
            $first = reset($roomFolders);
            $roomFolders = array_filter(glob($first . '/*'), 'is_dir');
        }

        foreach ($roomFolders as $roomFolder) {
            $roomName = basename($roomFolder);
            $roomId = $this->getRoomIdFromFolderName($roomName);
            if (!$roomId) continue;

            // Lấy danh sách folder khu vực trong phòng
            $areaFolders = array_filter(glob($roomFolder . '/*'), 'is_dir');
            foreach ($areaFolders as $areaFolder) {
                $areaName = basename($areaFolder);
                $areaGroupId = $this->getAreaGroupIdFromFolder($areaName);
                if (!$areaGroupId) continue;

                // Lấy ảnh trong từng folder khu vực (bỏ qua .DS_Store và file ẩn)
                $images = array_filter(
                    glob($areaFolder . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE),
                    function ($path) {
                        $basename = basename($path);
                        return $basename[0] !== '.' && !str_contains($basename, '__MACOSX');
                    }
                );

                $index = 0;
                foreach ($images as $imagePath) {
                    // Kiểm tra MIME để chắc chắn là ảnh thật
                    $mime = mime_content_type($imagePath);
                    if (!str_starts_with($mime, 'image/')) {
                        Log::warning("Bỏ qua file không phải ảnh: {$imagePath} ({$mime})");
                        continue;
                    }

                    $uploadedFile = new UploadedFile(
                        $imagePath,
                        basename($imagePath),
                        $mime,
                        null,
                        true
                    );

                    try {
                        $svc->upload($uploadedFile, "room-images/{$roomId}", 's3', [
                            'room_id' => $roomId,
                            'room_image_area_group_id' => $areaGroupId,
                            'index' => $index++,
                        ]);
                    } catch (Throwable $e) {
                        Log::error("Lỗi upload file {$imagePath}: " . $e->getMessage());
                    }
                }
            }
        }

        // Dọn thư mục tạm
        File::deleteDirectory($extractPath);
        
        // Xóa file zip gốc
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }

        Log::info("Job ProcessRoomZipImageJob: Hoàn tất xử lý file {$this->filePath}");
    }

    private function getRoomIdFromFolderName(string $folderName, ?string $phoneNumberHost = null)
    {
        $folderName = trim($folderName);

        $query = Room::whereRaw('LOWER(name) = ?', [mb_strtolower($folderName)]);

        if ($phoneNumberHost) {
            $query->whereHas('host', function ($q) use ($phoneNumberHost) {
                $q->where('phone_number', $phoneNumberHost);
            });
        }

        $room = $query->first();

        return $room ? $room->id : null;
    }

    private function getAreaGroupIdFromFolder(string $folderName)
    {
        $code = mb_strtolower(trim($folderName));

        $group = RoomImageGroup::where('code', $code)->first();

        return $group ? $group->id : null;
    }
}

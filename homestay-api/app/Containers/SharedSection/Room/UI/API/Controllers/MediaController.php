<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Jobs\ProcessRoomZipImageJob;
use App\Containers\SharedSection\Room\Models\Media;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomHighlightAmenity;
use App\Containers\SharedSection\Room\Models\RoomImageGroup;
use App\Containers\SharedSection\Room\UI\API\Requests\MediaRequest;
use App\Ship\Parents\Controllers\ApiController;
use App\Ship\Services\ImageService;
use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Http\UploadedFile;
use Throwable;
use Log;
use File;
final class MediaController extends ApiController
{
    public function store(MediaRequest $r, ImageService $svc)
    {
        $dir='';

        if(!empty($r['highlight_amenity_id'])) {
            $highlightAmenity = RoomHighlightAmenity::find($r['highlight_amenity_id']);
            $roomId = $highlightAmenity->room_id;
            $dir = 'room-images/'.$roomId.'/highlight-amenities/'.$r['highlight_amenity_id'];

        }else{
            $roomId = Room::find($r['room_id'])->id;
            $dir = 'room-images/'.$roomId;
        }
        $data = array_merge( $r->except('images'));
        foreach ($r->images as $i => $image) {
            $media = $svc->upload(
                $image['file'],
                $dir,
                's3',
                   array_merge([$data,
                       'index' => $image['index'] ?? $i])
            );
            $uploaded[] = $media->id;
        }

        return response()->json(['ids' => $uploaded], 201);
    }

    public function responsive(Media $media, Request $r, ImageService $svc)
    {
        $sizes = $r->query('sizes', '(max-width: 768px) 100vw, 720px');
        $target = $r->query('target', 'content-1440');

        return response()->json(
            $svc->toResponsivePayload($media, $sizes, $target)
        );
    }
    function getRoomIdFromFolderName(string $folderName, ?string $phoneNumberHost = null)
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
    function getAreaGroupIdFromFolder(string $folderName)
    {
        $code = mb_strtolower(trim($folderName));

        $group = RoomImageGroup::where('code', $code)->first();

        return $group ? $group->id : null;
    }

    public function uploadZipImage(Request $request, ImageService $svc)
    {
        $zipFile = $request->file('file');

        if (!$zipFile) {
            return response()->json(['error' => 'Vui lòng upload file zip hợp lệ'], 400);
        }

        // Tạo thư mục tạm để lưu file ZIP (sẽ được tự động dọn bởi Job)
        $tempZipDir = storage_path('app/tmp_zips');
        if (!file_exists($tempZipDir)) {
            mkdir($tempZipDir, 0777, true);
        }

        $fileName = 'room_import_' . time() . '_' . uniqid() . '.zip';
        $fullPath = $tempZipDir . '/' . $fileName;

        // Lưu file upload vào thư mục tạm trên Server
        move_uploaded_file($zipFile->getRealPath(), $fullPath);

        // Ném công việc xử lý file ZIP vô Hàng đợi (Queue Job)
        ProcessRoomZipImageJob::dispatch($fullPath);

        return response()->json([
            'success' => true, 
            'message' => 'Đã tải lên file ZIP thành công. Hệ thống đang tiến hành xử lý ngầm và upload hình ảnh. Bạn có thể làm việc khác, kết quả sẽ có sau ít phút.'
        ]);
    }

    public function uploadHighlightAmenity(Request $request, ImageService $svc)
    {
        $zipFile = $request->file('file');

        $zip = new ZipArchive();
        if ($zip->open($zipFile->getRealPath()) === true) {
            $extractPath = storage_path('app/tmp/highlight_' . time());
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return response()->json(['message' => 'Cannot open zip file'], 400);
        }

        $macosxPath = $extractPath . '/__MACOSX';
        if (file_exists($macosxPath)) {
            \File::deleteDirectory($macosxPath);
        }

        $uploaded = [];

        $roomFolders = array_filter(glob($extractPath . '/*'), 'is_dir');
        if (count($roomFolders) === 1) {
            $first = reset($roomFolders);
            $roomFolders = array_filter(glob($first . '/*'), 'is_dir');
        }
        foreach($roomFolders as $roomFolder){
            $folderName = basename($roomFolder);

            $roomId = $this->getRoomIdFromFolderName($folderName);

            if (!$roomId) {
                continue;
            }

            $room = Room::with('highlightAmenities', 'highlightAmenities.amenity')->find($roomId);
            if (!$room) {
                continue;
            }

            $highlightAmenities = $room->highlightAmenities;

            $images = array_filter(
                glob($roomFolder . '/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE),
                fn($path) => basename($path)[0] !== '.' && !str_contains(basename($path), '__MACOSX')
            );
            foreach ($images as $imagePath) {
                $mime = mime_content_type($imagePath);
                if (!str_starts_with($mime, 'image/')) continue;

                // Lấy tên file (ko có phần mở rộng) => chính là amenity code
                $filename = pathinfo($imagePath, PATHINFO_FILENAME);

                $highlight = $highlightAmenities->firstWhere('amenity.code', $filename);
                if (!$highlight) {
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
                    $svc->upload(
                        $uploadedFile,
                        "room-images/{$roomId}/highlight_amenities/{$highlight->id}",
                        's3',
                        [
                            'room_id' => $roomId,
                            'highlight_amenity_id' => $highlight->id,
                        ]
                    );

                    $uploaded[] = [
                        'room_id' => $roomId,
                        'room_name' => $room->name,
                        'amenity_code' => $filename,
                        'file' => basename($imagePath),
                    ];
                } catch (Throwable $e) {
                    Log::error("Lỗi upload ảnh highlight: {$imagePath} - " . $e->getMessage());
                }
            }
        }

        File::deleteDirectory($extractPath);

        return response()->json([
            'message' => 'Upload highlight amenities successfully',
            'uploaded' => $uploaded,
        ]);
    }
}

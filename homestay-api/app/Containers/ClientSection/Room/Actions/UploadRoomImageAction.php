<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Ship\Parents\Actions\Action as ParentAction;
use App\Ship\Helpers\S3Helper;
use App\Containers\SharedSection\Room\Models\RoomImage;
use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UploadRoomImageAction extends ParentAction
{
    public function __construct(
        private readonly S3Helper $s3Helper
    ) {
    }

    public function run(array $data): array
    {
        $room = Room::findOrFail($data['room_id']);
        $uploadedImages = [];
        $errors = [];

        DB::beginTransaction();

        try {
            // Generate area group ID if not provided
            $areaGroupId = $data['area_group_id'] ?? Str::uuid()->toString();

            foreach ($data['images'] as $index => $imageFile) {
                // Upload to S3
                $s3Result = $this->s3Helper->uploadFile(
                    $imageFile,
                    'room-images/' . $room->id
                );

                if ($s3Result['success']) {
                    // Create room image record
                    $roomImage = RoomImage::create([
                        'id' => Str::uuid()->toString(),
                        'room_id' => $room->id,
                        'room_image_area_group_id' => $areaGroupId,
                        'is_cover' => ($index === 0 && isset($data['is_cover'])) ? $data['is_cover'] : false,
                        'file_path' => $s3Result['file_path'],
                        'file_size' => $s3Result['file_size']    ,
                        'content_type' => $s3Result['content_type']
                    ]);


                    $uploadedImages[] = [
                        'id' => $roomImage->id,
                        'file_name' => $roomImage->file_name,
                        'image_url' => $roomImage->image_url,
                        'file_size' => $roomImage->file_size,
                        'content_type' => $roomImage->content_type,
                        'is_cover' => $roomImage->is_cover,
                    ];
                } else {
                    $errors[] = [
                        'index' => $index,
                        'file_name' => $imageFile->getClientOriginalName(),
                        'error' => $s3Result['error'],
                    ];
                }
            }

            // If all uploads failed, rollback
            if (empty($uploadedImages)) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'All image uploads failed',
                    'errors' => $errors,
                ];
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Images uploaded successfully',
                'data' => [
                    'room_id' => $room->id,
                    'area_group_id' => $areaGroupId,
                    'uploaded_images' => $uploadedImages,
                    'total_uploaded' => count($uploadedImages),
                    'errors' => $errors,
                ],
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded S3 files if database transaction failed
            foreach ($uploadedImages as $image) {
                if (isset($image['s3_path'])) {
                    $this->s3Helper->deleteFile($image['s3_path']);
                }
            }

            return [
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
                'errors' => [],
            ];
        }
    }
}

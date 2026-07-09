<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomHighlightAmenity;
use App\Containers\SharedSection\Room\Models\RoomHighlightAmenityImage;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class UploadImageHighlightAmenityAction extends ParentAction
{
    public function __construct(
        private readonly S3Helper $s3Helper
    ) {
    }

    public function run(array $data): array
    {
        $highlightAmenity = RoomHighlightAmenity::findOrFail($data['highlight_amenity_id']);
        $room = Room::find($highlightAmenity->room_id, 'id');
        $uploadedImages = [];
        $errors = [];

        DB::beginTransaction();

        try {

            foreach ($data['images'] as $index => $imageFile) {
                // Upload to S3
                $s3Result = $this->s3Helper->uploadFile(
                    $imageFile,
                    'room-images/' .$room->id.'/highlight-amenity-images/'. $highlightAmenity->id
                );

                if ($s3Result['success']) {
                    // Create room image record
                    $roomImage = RoomHighlightAmenityImage::create([
                        'id' => Str::uuid()->toString(),
                        'highlight_amenity_id' => $highlightAmenity->id,
                        'file_path' => $s3Result['file_path'],
                        'file_size' => $s3Result['file_size']    ,
                        'content_type' => $s3Result['content_type']
                    ]);


                    $uploadedImages[] = [
                        'id' => $roomImage->id,
                        'file_name' => $roomImage->file_name,
                        'file_size' => $roomImage->file_size,
                        'content_type' => $roomImage->content_type,
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
                    'highlight_amenity_id' => $highlightAmenity->id,
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

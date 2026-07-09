<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\RoomImage;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use Illuminate\Support\Facades\Log;

class RoomImageTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
    ];

    protected array $availableIncludes = [];

    public function transform(RoomImage $roomImage): array
    {
        // Get image URL if file_path exists
        $imageUrl = null;
        if (!empty($roomImage->file_path)) {
            try {
                $s3Helper = app(S3Helper::class);
                $imageUrl = $s3Helper->getPresignedUrl($roomImage->file_path, 1440); // 24 hours
            } catch (\Exception $e) {
                // Log error but don't break the response
                Log::warning('Failed to get S3 URL for room image: ' . $e->getMessage(), [
                    'file_path' => $roomImage->file_path,
                    'room_image_id' => $roomImage->id
                ]);
                $imageUrl = null;
            }
        }
        
        return [
            'object' => $roomImage->getResourceKey(),
            'id' => $roomImage->id,
            'room_image_area_group_id' => $roomImage->room_image_area_group_id,
            'file_path' => $roomImage->file_path,
            'image_url' => $imageUrl,
            'is_cover' => $roomImage->is_cover,
            'file_name' => $roomImage->file_name,
            'file_size' => $roomImage->file_size,
            'content_type' => $roomImage->content_type,
        ];
    }

}

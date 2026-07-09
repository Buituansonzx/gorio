<?php

namespace App\Containers\MobileSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use App\Ship\Services\ImageService;

final class ListFavoriteRoomTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
        'images',
    ];

    protected array $availableIncludes = [];

    public function transform(Room $room): array
    {
        $avgRating = $room->reviews->avg('rating') ?? 0;
        $countReviews = $room->reviews->count();
        $isLovedByEveryone = false;
        if($avgRating >= 4.7){
            $isLovedByEveryone = true;
        }
        return [
            'type' => $room->getResourceKey(),
            'id' => $room->id,
            'name' => $room->name,
            'title' => $room->title,
            'isLovedByEveryone' => $isLovedByEveryone,
            'address' => $room->address,
            'avg_rating' => round($avgRating, 1),
            'review_count' => $countReviews,
        ];
    }

    public function includeImages(Room $room)
    {
        if ($room->medias && $room->medias->isNotEmpty()) {
            $imagesArray = $room->medias->map(function ($media) {
                return [
                    'object' => $media->getResourceKey(),
                    'id' => $media->id,
                    'room_image_area_group_id' => $media->room_image_area_group_id,
                    'image_url' => app(ImageService::class)->toMobilePayload($media)['img']['src'],
                    'is_cover' => $media->is_cover,
                ];
            })->toArray();

            return $this->primitive($imagesArray);
        }
        return $this->primitive([]);
    }
}

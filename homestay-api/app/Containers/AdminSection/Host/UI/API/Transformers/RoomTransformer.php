<?php

namespace App\Containers\AdminSection\Host\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use App\Ship\Services\ImageService;

final class RoomTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Room $room): array
    {
        $firstImage = $room->medias?->firstWhere('is_cover', true);
        if ($firstImage) {
            $image = app(ImageService::class)->toResponsivePayload(
                $firstImage,
                '(max-width: 480px) 100vw, (max-width: 1024px) 50vw, 33vw',
                'content-1440'
            )['sources'];
        } else {
            $image = []; // hoặc gán ảnh mặc định
        }
        return [
            'type' => $room->getResourceKey(),
            'id' => $room->id,
            'code' => $room->code,
            'name' => $room->name,
            'district' => $room->district->getTranslation('name', request()->header('Accept-Language')),
            'province' => $room->district->province->getTranslation('name', request()->header('Accept-Language')),
            'checkin_instruction' => $room->checkinMethods->first()?->pivot->way_to_house_message,
            'image' => $image,
            'address' => $room->address,
            'host_name' => $room->host->user->name,
            'brand_name' => $room->host->business_name,
            'is_active' => $room->is_active,
            'created_at' => $room->created_at,
            'updated_at' => $room->updated_at,
        ];
    }
}

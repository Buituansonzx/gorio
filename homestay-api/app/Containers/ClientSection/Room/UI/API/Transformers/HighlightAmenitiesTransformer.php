<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\Amenity;
use App\Containers\SharedSection\Room\Models\RoomHighlightAmenity;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class HighlightAmenitiesTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
        'images',
    ];

    protected array $availableIncludes = [];

    public function transform(RoomHighlightAmenity $roomHighlightAmenity): array
    {
        return [
            'type' => $roomHighlightAmenity->getResourceKey(),
            'id' => $roomHighlightAmenity->amenity->id,
            'name' => $roomHighlightAmenity->amenity->getTranslation('name', request()->header('Accept-Language')),
            'service_type' => $roomHighlightAmenity->service_type,
            'is_free' => $roomHighlightAmenity->is_free,
            'price' => $roomHighlightAmenity->price,
            'unit' => $roomHighlightAmenity->unit,
            'status' => $roomHighlightAmenity->status,
        ];
    }

    public function includeImages(RoomHighlightAmenity $roomHighlightAmenity)
    {
        return $this->collection($roomHighlightAmenity->images, new ImageHighlightAmenityTransformer());
    }
}

<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\Amenity;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class AmenitiesTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Amenity $amenity): array
    {
        return [
            'type' => $amenity->getResourceKey(),
            'id' => $amenity->id,
            'name' => $amenity->getTranslation('name', request()->header('Accept-Language')),
            'is_basic' => $amenity->is_basic,
        ];
    }
}

<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\RoomSurroundingFacility;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class RoomSurroundingFacilityTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [
        'surroundingFacility',
    ];

    public function transform(RoomSurroundingFacility $roomSurroundingFacility): array
    {
        return [
            'object' => $roomSurroundingFacility->getResourceKey(),
            'facility_id' => $roomSurroundingFacility->facility_id,
        ];
    }

    public function includeSurroundingFacility(RoomSurroundingFacility $roomSurroundingFacility): \League\Fractal\Resource\Item
    {
        return $this->item($roomSurroundingFacility->surroundingFacility, new SurroundingFacilityTransformer());
    }
}

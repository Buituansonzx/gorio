<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomParkingRule;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ParkingRuleTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomParkingRule $parkingRule): array
    {
        return [
            'type' => $parkingRule->getResourceKey(),
            'is_free' => $parkingRule->is_free,
            'price' => $parkingRule->price,
            'currency' => $parkingRule->currency,
            'location' => $parkingRule->location,
            'distance_meters' =>$parkingRule->distance_meters,
            'description' => $parkingRule->description
        ];
    }
}

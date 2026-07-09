<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomComboPricing;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class RoomComboPricingTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomComboPricing $roomComboPricing): array
    {
        return [
            'type' => $roomComboPricing->getResourceKey(),
            'id' => $roomComboPricing->id,
            'start_time' => $roomComboPricing->start_time,
            'end_time' => $roomComboPricing->end_time,
            'price' => $roomComboPricing->price,
        ];
    }
}

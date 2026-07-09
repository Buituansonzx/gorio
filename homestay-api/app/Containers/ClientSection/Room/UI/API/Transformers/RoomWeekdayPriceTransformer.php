<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\RoomWeekdayPrice;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class RoomWeekdayPriceTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomWeekdayPrice $roomWeekdayPrice): array
    {
        return [
            'object' => $roomWeekdayPrice->getResourceKey(),
            'id' => $roomWeekdayPrice->getHashedKey(),
            'weekday' => $roomWeekdayPrice->weekday,
            'price' => $roomWeekdayPrice->price,
            'formatted_price' => number_format($roomWeekdayPrice->price, 0, ',', '.') . ' VND',
            'is_active' => $roomWeekdayPrice->is_active,
            'created_at' => $roomWeekdayPrice->created_at?->toISOString(),
            'updated_at' => $roomWeekdayPrice->updated_at?->toISOString(),
        ];
    }
}

<?php

namespace App\Containers\AdminSection\Room\UI\API\Transformers;

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
            'room_id' => $roomComboPricing->room_id,
            'start_time' => $roomComboPricing->start_time,
            'end_time' => $roomComboPricing->end_time,
            'price' => $roomComboPricing->price,
            'currency' => $roomComboPricing->currency,
            'mon_price' => $roomComboPricing->mon_price,
            'tue_price' => $roomComboPricing->tue_price,
            'wed_price' => $roomComboPricing->wed_price,
            'thu_price' => $roomComboPricing->thu_price,
            'fri_price' => $roomComboPricing->fri_price,
            'sat_price' => $roomComboPricing->sat_price,
            'sun_price' => $roomComboPricing->sun_price,
            'mon_buffer_price' => $roomComboPricing->mon_buffer_price,
            'tue_buffer_price' => $roomComboPricing->tue_buffer_price,
            'wed_buffer_price' => $roomComboPricing->wed_buffer_price,
            'thu_buffer_price' => $roomComboPricing->thu_buffer_price,
            'fri_buffer_price' => $roomComboPricing->fri_buffer_price,
            'sat_buffer_price' => $roomComboPricing->sat_buffer_price,
            'sun_buffer_price' => $roomComboPricing->sun_buffer_price,
        ];
    }
}

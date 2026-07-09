<?php

namespace App\Containers\AdminSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\RoomHourlyPricing;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class HourlyPriceTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomHourlyPricing $hourlypricing): array
    {
        return [
            'type' => $hourlypricing->getResourceKey(),
            'id' => $hourlypricing->id,
            'created_at' => $hourlypricing->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $hourlypricing->updated_at?->format('Y-m-d H:i:s') ?? null,
            'min_hours_price' => $hourlypricing->min_hours_price,
            'mon_min_hour_price' => $hourlypricing->mon_min_hour_price,
            'tue_min_hour_price' => $hourlypricing->tue_min_hour_price,
            'wed_min_hour_price' => $hourlypricing->wed_min_hour_price,
            'thu_min_hour_price' => $hourlypricing->thu_min_hour_price,
            'fri_min_hour_price' => $hourlypricing->fri_min_hour_price,
            'sat_min_hour_price' => $hourlypricing->sat_min_hour_price,
            'sun_min_hour_price' => $hourlypricing->sun_min_hour_price,
            'mon_buffer_price' => $hourlypricing->mon_buffer_price,
            'tue_buffer_price' => $hourlypricing->tue_buffer_price,
            'wed_buffer_price' => $hourlypricing->wed_buffer_price,
            'thu_buffer_price' => $hourlypricing->thu_buffer_price,
            'fri_buffer_price' => $hourlypricing->fri_buffer_price,
            'sat_buffer_price' => $hourlypricing->sat_buffer_price,
            'sun_buffer_price' => $hourlypricing->sun_buffer_price,
        ];
    }
}

<?php

namespace App\Containers\AdminSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\FixedCheckTime;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class RoomFixedCheckTimeTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(FixedCheckTime $fixedCheckTime): array
    {
        $locale = request()->header('Accept-Language');
        $pivot = $fixedCheckTime->pivot;

        return [
            'type' => $fixedCheckTime->getResourceKey(),
            'id' => $fixedCheckTime->id,
            'code' => $fixedCheckTime->code,
            'name' => $fixedCheckTime->getTranslation('name', $locale),
            'description' => $fixedCheckTime->getTranslation('description', $locale),
            'is_active' => (bool) $fixedCheckTime->is_active,
            'price' => $pivot->price,
            'mon_price' => $pivot->mon_price,
            'tue_price' => $pivot->tue_price,
            'wed_price' => $pivot->wed_price,
            'thu_price' => $pivot->thu_price,
            'fri_price' => $pivot->fri_price,
            'sat_price' => $pivot->sat_price,
            'sun_price' => $pivot->sun_price,
            'mon_buffer_price' => $pivot->mon_buffer_price,
            'tue_buffer_price' => $pivot->tue_buffer_price,
            'wed_buffer_price' => $pivot->wed_buffer_price,
            'thu_buffer_price' => $pivot->thu_buffer_price,
            'fri_buffer_price' => $pivot->fri_buffer_price,
            'sat_buffer_price' => $pivot->sat_buffer_price,
            'sun_buffer_price' => $pivot->sun_buffer_price,
        ];
    }
}

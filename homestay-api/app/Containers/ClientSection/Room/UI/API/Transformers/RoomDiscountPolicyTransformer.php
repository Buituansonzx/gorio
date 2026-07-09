<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\RoomDiscountPolicy;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class RoomDiscountPolicyTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomDiscountPolicy $roomDiscountPolicy): array
    {
        return [
            'object' => $roomDiscountPolicy->getResourceKey(),
            'id' => $roomDiscountPolicy->getHashedKey(),
            'discount_type' => $roomDiscountPolicy->discount_type,
            'discount_value' => $roomDiscountPolicy->discount_value,
            'min_stay_days' => $roomDiscountPolicy->min_stay_days,
            'max_stay_days' => $roomDiscountPolicy->max_stay_days,
            'start_date' => $roomDiscountPolicy->start_date?->toDateString(),
            'end_date' => $roomDiscountPolicy->end_date?->toDateString(),
            'is_active' => $roomDiscountPolicy->is_active,
            'created_at' => $roomDiscountPolicy->created_at?->toISOString(),
            'updated_at' => $roomDiscountPolicy->updated_at?->toISOString(),
        ];
    }
}

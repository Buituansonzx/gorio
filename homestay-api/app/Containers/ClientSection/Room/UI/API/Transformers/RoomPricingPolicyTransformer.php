<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\RoomPricingPolicy;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class RoomPricingPolicyTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomPricingPolicy $roomPricingPolicy): array
    {
        return [
            'object' => $roomPricingPolicy->getResourceKey(),
            'id' => $roomPricingPolicy->id,
            'policy_name' => $roomPricingPolicy->policy_name,
            'description' => $roomPricingPolicy->description,
            'is_active' => $roomPricingPolicy->is_active,
            'created_at' => $roomPricingPolicy->created_at?->toISOString(),
            'updated_at' => $roomPricingPolicy->updated_at?->toISOString(),
        ];
    }
}

<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomPolicy;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class RoomPolicyTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomPolicy $roomPolicy): array
    {
        return [
            'type' => $roomPolicy->getResourceKey(),
            'policy_type' => $roomPolicy->policy_type,
            'content' => $roomPolicy->content,
        ];
    }
}

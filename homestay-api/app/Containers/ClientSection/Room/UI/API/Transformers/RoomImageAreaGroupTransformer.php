<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomImageGroup;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class RoomImageAreaGroupTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomImageGroup $group): array
    {
        return [
            'type' => $group->getResourceKey(),
            'id' => $group->id,
            'code' => $group->code,
            'name' => $group->getTranslation('name', request()->header('Accept-Language')),
        ];
    }
}

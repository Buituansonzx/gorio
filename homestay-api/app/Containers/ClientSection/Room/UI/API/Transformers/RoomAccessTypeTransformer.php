<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\RoomAccessType;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class RoomAccessTypeTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomAccessType $roomAccessType): array
    {
        return [
            'object' => $roomAccessType->getResourceKey(),
            'id' => $roomAccessType->id,
            'name' => $roomAccessType->getTranslation('name', request()->header('Accept-Language')),
            'description' => $roomAccessType->getTranslation('description', request()->header('Accept-Language')),
        ];
    }
}

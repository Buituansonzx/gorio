<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\FixedCheckTime;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class RoomFixedCheckTimeTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(FixedCheckTime $fixedCheckTime): array
    {
        return [
            'type' => $fixedCheckTime->getResourceKey(),
            'id' => $fixedCheckTime->id,
            'name' => $fixedCheckTime->getTranslation('name', request()->header('Accept-Language')),
            'price' => $fixedCheckTime->pivot->price,
            'description' => $fixedCheckTime->getTranslation('description', request()->header('Accept-Language')),
        ];
    }
}

<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomImageGroup;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ImageAreaGroupTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(RoomImageGroup $roomImageGroup): array
    {
        return [
            'type' => $roomImageGroup->getResourceKey(),
            'id' => $roomImageGroup->id,
            'code'  => $roomImageGroup->code,
            'name' => $roomImageGroup->getTranslation('name', request()->header('Accept-Language')),

        ];
    }
}

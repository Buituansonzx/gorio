<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\SurroundingFacility;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

class SurroundingFacilityTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(SurroundingFacility $surroundingFacility): array
    {
        return [
            'object' => $surroundingFacility->getResourceKey(),
            'id' => $surroundingFacility->id,
            'name' => $surroundingFacility-> getTranslation('name', request()->header('Accept-Language')),
        ];
    }
}

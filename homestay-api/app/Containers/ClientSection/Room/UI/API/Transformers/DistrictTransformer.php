<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\District;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class DistrictTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(District $district): array
    {
        return [
            'type' => $district->getResourceKey(),
            'id' => $district->id,
            'code' => $district->code,
            'name' => $district->getTranslation('name', request()->header('Accept-Language')),
        ];
    }
}

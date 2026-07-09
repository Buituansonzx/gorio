<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\Province;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class GetProvinceTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
        'districts'
    ];

    protected array $availableIncludes = [];

    public function transform(Province $province): array
    {
        return [
            'type' => $province->getResourceKey(),
            'id' => $province->id,
            'code' => $province->code,
            'name' => $province->getTranslation('name', request()->header('Accept-Language')),
        ];
    }

    public function includeDistricts(Province $province)
    {
        if($province->districts && $province->districts->isNotEmpty()){
            $districtArray = $province->districts->map(function ($district){
                return [
                    'type' => $district->getResourceKey(),
                    'id' => $district->id,
                    'code' => $district->code,
                    'name' => $district->getTranslation('name', request()->header('Accept-Language')),
                ];
            })->toArray();
            return $this->primitive($districtArray);
        }
        return $this->primitive([]);
    }
}

<?php

namespace App\Containers\SharedSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\Amenity;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class GetAmenitiesTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Amenity $amenity)
    {
        return [
            'type' => $amenity->getResourceKey(),
            'id' => $amenity->id,
            'name' => $amenity->getTranslation('name', request()->header('Accept-Language')) ,
            'code' => $amenity->code,
            'icon' => $amenity->icon,
        ];
    }
    public function transformCollection(array $amenities)
    {
        $grouped = collect($amenities)
            ->groupBy(function ($amenity) {
                $groupName = $amenity->amenityGroup?->getTranslation('name', request()->header('Accept-Language'));
                return $groupName ?: 'Khác';
            })
            ->map(function ($group, $groupName) {
                return [
                    'group_name' => $groupName,
                    'items' => $group->values()->map(fn($item) => $this->transform($item))->all(),
                ];
            })
            ->values()
            ->all();

        return [
            'data' => $grouped
        ];
    }
}

<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\Amenity;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Amenity
 *
 * @extends ParentRepository<TModel>
 */
final class GetAmenitiesRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return Amenity::class;
    }

    public function getAmenities()
    {
        $amenities = $this->model->active()->with('group')->get()->groupBy('group');

        // Build result
        $result = $amenities->map(function ($groupAmenities, $groupName) {
            $group = $groupAmenities->first()->group;
            return [
                'group' => $group ? $group->getTranslation('name', request()->header('Accept-Language')) : 'Khác',
                'amenities' => $groupAmenities->map(function ($amenity) {
                    return [
                        'id' => $amenity->id,
                        'name' => $amenity->getTranslation('name', request()->header('Accept-Language')),
                        'is_basic' => $amenity->is_basic,
                        'icon' => asset($amenity->icon),
                    ];
                })->values()->toArray(),
            ];
        })->values()->toArray();

        return $result;
    }
}

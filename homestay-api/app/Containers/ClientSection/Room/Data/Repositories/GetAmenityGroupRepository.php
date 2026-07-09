<?php

namespace App\Containers\ClientSection\Room\Data\Repositories;

use App\Containers\ClientSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\AmenityGroup;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Room
 *
 * @extends ParentRepository<TModel>
 */
final class GetAmenityGroupRepository extends ParentRepository
{

    public function model(): string
    {
        return AmenityGroup::class;
    }
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    public function getAmenityGroups()
    {
        return $this->model->get();
    }
}

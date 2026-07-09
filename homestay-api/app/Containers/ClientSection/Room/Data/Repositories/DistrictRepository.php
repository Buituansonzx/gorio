<?php

namespace App\Containers\ClientSection\Room\Data\Repositories;

use App\Containers\ClientSection\Room\Models\District;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of District
 *
 * @extends ParentRepository<TModel>
 */
final class DistrictRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

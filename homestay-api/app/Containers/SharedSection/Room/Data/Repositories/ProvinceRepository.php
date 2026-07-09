<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\Province;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Province
 *
 * @extends ParentRepository<TModel>
 */
final class ProvinceRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

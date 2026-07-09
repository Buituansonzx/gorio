<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\AmenityGroup;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of AmenityGroup
 *
 * @extends ParentRepository<TModel>
 */
final class AmenityGroupRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

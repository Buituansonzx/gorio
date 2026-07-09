<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\Amenity;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Amenity
 *
 * @extends ParentRepository<TModel>
 */
final class AmenityRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

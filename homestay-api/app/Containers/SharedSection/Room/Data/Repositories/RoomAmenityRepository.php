<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomAmenity;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomAmenity
 *
 * @extends ParentRepository<TModel>
 */
final class RoomAmenityRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomImageGroup;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomImageGroup
 *
 * @extends ParentRepository<TModel>
 */
final class RoomImageGroupRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

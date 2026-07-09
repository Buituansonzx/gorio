<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomView;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomView
 *
 * @extends ParentRepository<TModel>
 */
final class RoomViewRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

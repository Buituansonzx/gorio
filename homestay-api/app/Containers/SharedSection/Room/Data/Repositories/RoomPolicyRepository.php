<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomPolicy;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomPolicy
 *
 * @extends ParentRepository<TModel>
 */
final class RoomPolicyRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

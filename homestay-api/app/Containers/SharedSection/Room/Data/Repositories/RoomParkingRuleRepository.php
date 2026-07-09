<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomParkingRule;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomParkingRule
 *
 * @extends ParentRepository<TModel>
 */
final class RoomParkingRuleRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

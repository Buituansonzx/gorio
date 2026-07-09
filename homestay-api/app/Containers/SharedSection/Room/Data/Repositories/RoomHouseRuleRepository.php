<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomHouseRule;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomHouseRule
 *
 * @extends ParentRepository<TModel>
 */
final class RoomHouseRuleRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomPriceHistory;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomPriceHistory
 *
 * @extends ParentRepository<TModel>
 */
final class RoomPriceHistoryRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'room_id' => '=',
        'policy_snapshot' => 'like',
        'changed_at' => 'like',
    ];

    public function model(): string
    {
        return RoomPriceHistory::class;
    }
}

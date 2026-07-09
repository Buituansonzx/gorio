<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomWeekdayPrice;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomWeekdayPrice
 *
 * @extends ParentRepository<TModel>
 */
final class RoomWeekdayPriceRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'policy_id' => '=',
        'weekday' => '=',
        'price' => '=',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomWeekdayPrice::class;
    }
}

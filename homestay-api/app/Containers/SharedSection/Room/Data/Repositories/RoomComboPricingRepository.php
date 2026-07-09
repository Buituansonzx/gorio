<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomComboPricing;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomComboPricing
 *
 * @extends ParentRepository<TModel>
 */
final class RoomComboPricingRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'room_id' => '=',
        'start_time' => '=',
        'end_time' => '=',
        'mon_price' => '=',
        'tue_price' => '=',
        'wed_price' => '=',
        'thu_price' => '=',
        'fri_price' => '=',
        'sat_price' => '=',
        'sun_price' => '=',
        'currency' => '=',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomComboPricing::class;
    }
}

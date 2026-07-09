<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomHourlyPricing;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomHourlyPricing
 *
 * @extends ParentRepository<TModel>
 */
final class RoomHourlyPricingRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'room_id' => '=',
        'min_hours' => '=',
        'min_hours_price' => '=',
        'next_hour_price' => '=',
        'currency' => '=',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomHourlyPricing::class;
    }
}

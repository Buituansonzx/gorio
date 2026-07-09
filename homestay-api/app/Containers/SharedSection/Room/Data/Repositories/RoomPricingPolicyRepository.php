<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomPricingPolicy;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomPricingPolicy
 *
 * @extends ParentRepository<TModel>
 */
final class RoomPricingPolicyRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'room_id' => '=',
        'checkin_time' => '=',
        'checkout_time' => '=',
        'base_price' => '=',
        'currency' => '=',
        'max_guests' => '=',
        'standard_guests' => '=',
        'extra_adult_price' => '=',
        'extra_child_price' => '=',
        'extra_hour_price' => '=',
        'cleaning_fee' => '=',
        'deposit_amount' => '=',
        'cleaning_gap_hours' => '=',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomPricingPolicy::class;
    }
}

<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomDiscountPolicy;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomDiscountPolicy
 *
 * @extends ParentRepository<TModel>
 */
final class RoomDiscountPolicyRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'room_id' => '=',
        'dayuse_checkin' => '=',
        'dayuse_checkout' => '=',
        'price_ratio_id' => '=',
        'late_checkin' => '=',
        'late_checkout' => '=',
        'late_discount' => '=',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomDiscountPolicy::class;
    }
}

<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomSpecialOffer;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomSpecialOffer
 *
 * @extends ParentRepository<TModel>
 */
final class RoomSpecialOfferRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'room_id' => '=',
        'last_minute_hours' => '=',
        'last_minute_discount_percent' => '=',
        'monthly_price' => '=',
        'currency' => '=',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return RoomSpecialOffer::class;
    }
}

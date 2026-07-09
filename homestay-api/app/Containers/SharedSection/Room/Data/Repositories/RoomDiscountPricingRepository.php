<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\RoomDiscountPricing;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of RoomDiscountPricing
 *
 * @extends ParentRepository<TModel>
 */
final class RoomDiscountPricingRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

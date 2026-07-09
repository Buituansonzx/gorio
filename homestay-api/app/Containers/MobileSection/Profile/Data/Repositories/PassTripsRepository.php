<?php

namespace App\Containers\MobileSection\Profile\Data\Repositories;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Order
 *
 * @extends ParentRepository<TModel>
 */
final class PassTripsRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];


    public function model(): string
    {
        return Order::class;
    }

    public function getPassTrips(string $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('check_out', '<', now())
            ->with([
                'room',
                'room.district',
                'room.host',
                'room.images' => function ($query) {
                    $query->where('is_cover', true);
                }
            ])
            ->get();
    }
}

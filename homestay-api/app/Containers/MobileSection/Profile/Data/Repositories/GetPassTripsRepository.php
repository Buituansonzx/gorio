<?php

namespace App\Containers\MobileSection\Profile\Data\Repositories;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of
 *
 * @extends ParentRepository<TModel>
 */
final class GetPassTripsRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];


    public function model(): string
    {
        return Order::class;
    }

    public function listingPassTrips(string $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('status', Order::STATUS_PAID)
            ->where('check_out', '<', now())
            ->with([
                'room',
                'room.district',
                'room.house',
                'room.district.province',
                'room.host.user',
                'room.medias' => function ($query) {
                    $query->where('is_cover', true)->limit(1);
                }
            ])
            ->get();
    }

    public function findPassTripByID($passTripId)
    {
        return $this->model
            ->with([
                'room',
                'room.district',
                'room.district.province',
                'room.host',
                'room.host.user',
                'room.roomPolicy',
                'room.medias',
            ])
            ->find($passTripId);
    }

}

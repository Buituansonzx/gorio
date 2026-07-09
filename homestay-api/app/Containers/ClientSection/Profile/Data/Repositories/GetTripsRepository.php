<?php

namespace App\Containers\ClientSection\Profile\Data\Repositories;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\CheckinMethod;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Order
 *
 * @extends ParentRepository<TModel>
 */
final class GetTripsRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];


    public function model(): string
    {
        return Order::class;
    }

    public function getTrips(string $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('status',Order::STATUS_PAID)
            ->where('check_out', '>', now()->subMinutes(CheckinMethod::LIMIT_MINUTES_AFTER_CHECKOUT))
            ->with([
                'room',
                'room.district',
                'room.district.province',
                'room.host.user',
                'room.house',
                'room.medias' => function ($query) {
                    $query->orderBy('sort_index', 'asc')->limit(4);
                },
            ])
            ->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, check_in, NOW())) ASC')
            ->get();
    }

    public function findTripByID($tripId)
    {
        return $this->model
            ->with([
                'room',
                'room.district',
                'room.host',
                'room.district.province',
                'room.host.user',
                'room.medias',
                'room.checkoutInstructionType',
                'room.roomPolicy',
                'room.roomCheckinInstruction',
                'room.roomCheckinInstruction.checkinMethod',
                'room.house',
            ])
            ->find($tripId);
    }
}

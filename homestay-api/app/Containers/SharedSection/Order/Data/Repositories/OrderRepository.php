<?php

namespace App\Containers\SharedSection\Order\Data\Repositories;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomLock;
use App\Ship\Parents\Repositories\Repository as ParentRepository;
use Carbon\Carbon;

/**
 * @template TModel of Order
 *
 * @extends ParentRepository<TModel>
 */
final class OrderRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    public function model(): string
    {
        return Order::class;
    }

    public function getBusyTime(string $roomId)
    {
        $now = Carbon::now();
        $sixMonthsLater = $now->copy()->addMonths(6);
        // Busy from orders
        $orders = $this->model->with('room')
            ->where('room_id', $roomId)->activeForBooking()
            ->where(function ($query) use ($now, $sixMonthsLater) {
                $query->whereBetween('check_in', [$now, $sixMonthsLater])
                    ->orWhereBetween('check_out', [$now, $sixMonthsLater])
                    ->orWhere(function ($sub) use ($now, $sixMonthsLater) {
                        $sub->where('check_in', '<', $now)
                            ->where('check_out', '>', $sixMonthsLater);
                    });
            })
            ->get();
        // Busy from room locks
        $locks = RoomLock::with('room')
            ->where('room_id', $roomId)
            ->where(function ($query) use ($now, $sixMonthsLater) {
                $query->whereBetween('start_time', [$now, $sixMonthsLater])
                    ->orWhereBetween('end_time', [$now, $sixMonthsLater])
                    ->orWhere(function ($sub) use ($now, $sixMonthsLater) {
                        $sub->where('start_time', '<', $now)
                            ->where('end_time', '>', $sixMonthsLater);
                    });
            })
            ->get();
        return $orders->merge($locks);
    }

    public function listingOrders(array $filters)
    {
        $query = $this->model->with('room', 'user','room.specialOffers');
        $query = $query->where('code', 'like', 'GR%');
        if(!empty($filters['status'])) {
            $query = $query->where('status', $filters['status']);
        }
        if(!empty($filters['room_id'])) {
            $query = $query->where('room_id', $filters['room_id']);
        }
        if(!empty($filters['created_from'])) {
            $query = $query->where('created_at', '>=', $filters['created_from']);
        }
        if (!empty($filters['key'])) {
            $key = $filters['key'];

            $query = $query->where(function ($q) use ($key) {
                $q->where('id', $key)
                    ->orWhere('code', 'like', "%$key%");

                $q->orWhereHas('user', function ($uq) use ($key) {
                    $uq->where('name', 'like', "%$key%")
                        ->orWhere('phone_number', 'like', "%$key%");
                });
            });
        }


        return $query->orderBy('created_at', 'desc')->paginate($filters['page_size'] ?? 10);
    }
}

<?php

namespace App\Containers\AdminSection\Dashboard\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Actions\Action as ParentAction;
use Carbon\Carbon;

final class TopRoomByOrderAction extends ParentAction
{
    public function run($data)
    {
        $date = Carbon::parse($data['start_date'] ?? now()->toDateString());
        switch ($data['type']) {
            case 'day':
                $start = $date->copy()->startOfDay();
                $end = $date->copy()->endOfDay();
                break;
            case 'month':
                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();
                break;
            case 'year':
                $start = $date->copy()->startOfYear();
                $end = $date->copy()->endOfYear();
                break;
            default:
                throw new \InvalidArgumentException("Invalid type: " . $data['type']);
        }
        $rooms = Room::selectRaw('
                rooms.name as name,
                hosts.business_name as business_name,
                COUNT(orders.id) as total_orders
            ')
            ->join('hosts', 'hosts.id', '=', 'rooms.host_id')
            ->join('users', 'users.id', '=', 'hosts.user_id')
            ->join('orders', 'orders.room_id', '=', 'rooms.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', Order::STATUS_PAID)
            ->where('orders.code', 'like', 'GR%')
             ->whereDate('orders.created_at', '>=', '2025-12-08')
            ->groupBy('rooms.id', 'rooms.name', 'hosts.business_name', 'users.name')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();

        return $rooms;
    }
}

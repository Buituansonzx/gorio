<?php

namespace App\Containers\AdminSection\Dashboard\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Actions\Action as ParentAction;
use Carbon\Carbon;

final class TopHostAction extends ParentAction
{
    public function run($data)
    {
        $date = Carbon::parse($data['start_date'] ?? now()->toDateString());

        switch ($data['type']) {
            case 'day':
                $start = $date->copy()->startOfDay();
                $end   = $date->copy()->endOfDay();
                break;

            case 'month':
                $start = $date->copy()->startOfMonth();
                $end   = $date->copy()->endOfMonth();
                break;

            case 'year':
                $start = $date->copy()->startOfYear();
                $end   = $date->copy()->endOfYear();
                break;

            default:
                throw new \InvalidArgumentException("Invalid type: {$data['type']}");
        }

        $hosts = Host::selectRaw('
                users.name as name,
                SUM(orders.total) as value,
                COUNT(orders.id) as total_orders
            ')
            ->join('users', 'users.id', '=', 'hosts.user_id')
            ->join('rooms', 'rooms.host_id', '=', 'hosts.id')
            ->join('orders', 'orders.room_id', '=', 'rooms.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', Order::STATUS_PAID)
            ->where('orders.code', 'like', 'GR%')
            ->whereDate('orders.created_at', '>=', '2025-12-08')
            ->groupBy('users.name')
            ->orderByDesc('value')
            ->limit(5)
            ->get();

        return $hosts;
    }

}

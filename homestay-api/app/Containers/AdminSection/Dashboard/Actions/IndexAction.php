<?php

namespace App\Containers\AdminSection\Dashboard\Actions;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Actions\Action as ParentAction;
use Carbon\Carbon;

final class IndexAction extends ParentAction
{
    function calcChangePercent($current, $previous)
    {
        $current = (float)$current;
        $previous = (float)$previous;
        if ($previous == 0) {
            return $current;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    public function run($data)
    {
        $date = Carbon::parse($data['start_date'] ?? now()->toDateString());

        $currentCountUser = User::notSeeded()->count();
        $currentCountHost = Host::count();
        $currentCountRoom = Room::count();

        switch ($data['type']) {

            case 'day':
                $prevDate = $date->copy()->subDay();

                $previousCountUser = User::notSeeded()->whereDate('created_at', '<', $date)->count();
                $previousCountHost = Host::whereDate('created_at', '<', $date)->count();

                $currentCountOrder = Order::whereDate('created_at', $date)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->whereDate('created_at', '>=', '2025-12-08')
                    ->count();

                $previousCountOrder = Order::whereDate('created_at', $prevDate)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->whereDate('created_at', '>=', '2025-12-08')
                    ->count();

                $currentRevenue = (float) Order::whereDate('created_at', $date)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->whereDate('created_at', '>=', '2025-12-08')
                    ->sum('total');

                $previousRevenue = (float) Order::whereDate('created_at', $prevDate)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->whereDate('created_at', '>=', '2025-12-08')
                    ->sum('total');

                $previousCountRoom = Room::whereDate('created_at', '<', $date)->count();
                break;

            case 'month':
                $prevDate = $date->copy()->subMonth();

                $previousCountUser = User::notSeeded()->where(function ($q) use ($date) {
                    $q->whereYear('created_at', '<', $date->year)
                        ->orWhere(function ($q) use ($date) {
                            $q->whereYear('created_at', $date->year)
                                ->whereMonth('created_at', '<', $date->month);
                        });
                })->count();

                $previousCountHost = Host::where(function ($q) use ($date) {
                    $q->whereYear('created_at', '<', $date->year)
                        ->orWhere(function ($q) use ($date) {
                            $q->whereYear('created_at', $date->year)
                                ->whereMonth('created_at', '<', $date->month);
                        });
                })->count();

                $currentCountOrder = Order::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->whereDate('created_at', '>=', '2025-12-08')
                    ->count();

                $previousCountOrder = Order::whereYear('created_at', $prevDate->year)
                    ->whereMonth('created_at', $prevDate->month)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->whereDate('created_at', '>=', '2025-12-08')
                    ->count();

                $currentRevenue = (float) Order::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->sum('total');

                $previousRevenue = (float) Order::whereYear('created_at', $prevDate->year)
                    ->whereMonth('created_at', $prevDate->month)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->sum('total');

                $previousCountRoom = Room::where(function ($q) use ($date) {
                    $q->whereYear('created_at', '<', $date->year)
                        ->orWhere(function ($q) use ($date) {
                            $q->whereYear('created_at', $date->year)
                                ->whereMonth('created_at', '<', $date->month);
                        });
                })->count();
                break;

            case 'year':
                $prevYear = $date->year - 1;

                $previousCountUser = User::notSeeded()->whereYear('created_at', '<', $date->year)->count();
                $previousCountHost = Host::whereYear('created_at', '<', $date->year)->count();

                $currentCountOrder = Order::whereYear('created_at', $date->year)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->count();

                $previousCountOrder = Order::whereYear('created_at', $prevYear)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->count();

                $currentRevenue = (float) Order::whereYear('created_at', $date->year)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->sum('total');

                $previousRevenue = (float) Order::whereYear('created_at', $prevYear)
                    ->where('status', Order::STATUS_PAID)
                    ->where('code', 'like', 'GR%')
                    ->sum('total');

                $previousCountRoom = Room::whereYear('created_at', '<', $date->year)->count();
                break;

            default:
                throw new \InvalidArgumentException("Invalid type: {$data['type']}");
        }

        $currentFillRate = $currentCountRoom > 0
            ? round(($currentCountOrder / $currentCountRoom) * 100, 2)
            : 0;

        $previousFillRate = $previousCountRoom > 0
            ? round(($previousCountOrder / $previousCountRoom) * 100, 2)
            : 0;

        return [
            'users' => [
                'total' => $currentCountUser,
                'change_percent' => $this->calcChangePercent($currentCountUser, $previousCountUser),
                'change' => $currentCountUser - $previousCountUser,
            ],
            'hosts' => [
                'total' => $currentCountHost,
                'change_percent' => $this->calcChangePercent($currentCountHost, $previousCountHost),
                'change' => $currentCountHost - $previousCountHost,
            ],
            'orders' => [
                'total' => $currentCountOrder,
                'change_percent' => $this->calcChangePercent($currentCountOrder, $previousCountOrder),
                'change' => $currentCountOrder - $previousCountOrder,
            ],
            'revenue' => [
                'total' => $currentRevenue,
                'change_percent' => $this->calcChangePercent($currentRevenue, $previousRevenue),
                'change' => $currentRevenue - $previousRevenue,
            ],
            'fill_rate' => [
                'percent' => $currentFillRate,
                'change_percent' => $this->calcChangePercent($currentFillRate, $previousFillRate),
                'change' => $currentFillRate - $previousFillRate,
            ],
        ];
    }
}

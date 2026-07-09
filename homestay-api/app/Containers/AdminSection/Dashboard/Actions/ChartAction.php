<?php

namespace App\Containers\AdminSection\Dashboard\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Actions\Action as ParentAction;
use Carbon\Carbon;
use function React\Promise\all;

final class ChartAction extends ParentAction
{
    public function run($data)
    {
        $startInput = !empty($data['start_date'])
            ? Carbon::parse($data['start_date'])
            : null;

        $endInput = !empty($data['end_date'])
            ? Carbon::parse($data['end_date'])
            : null;

        switch ($data['type']) {
            case 'day':
                $start = $startInput->copy()->startOfDay();
                $end   = $endInput->copy()->endOfDay();
                //Hiển thị theo day nếu chọn display = day, ngược lại hiển thị theo hour
                if($data['display'] == 'day'){
                    $totalPeriods = $start->diffInDays($end) + 1;

                    return $this->generateChartData(
                        keyType: 'DAY',
                        start: $start,
                        end: $end,
                        totalPeriods: $totalPeriods,
                        labelCallback: fn ($d) => $d->format('d/m')
                    );

                }else{
                    $totalPeriods = $start->diffInHours($end) + 1;
                    return $this->generateChartData(
                        'HOUR',
                        $start,
                        $end,
                        $totalPeriods,
                        labelCallback: fn ($d) => $d->format('d/m H:00')
                    );
                }


            case 'month':
                $start = $startInput->copy()->startOfMonth();
                $end   = $endInput->copy()->endOfMonth();

                if($data['display'] == 'month'){
                    $totalPeriods = $start->diffInMonths($end) + 1;

                    return $this->generateChartData(
                        'MONTH',
                        $start,
                        $end,
                        $totalPeriods,
                        labelCallback: fn ($d) => $d->format('m/Y')
                    );
                }
                $totalPeriods = $start->diffInDays($end) + 1;

                return $this->generateChartData(
                    'DAY',
                    $start,
                    $end,
                    $totalPeriods,
                    labelCallback: fn ($d) => $d->format('d/m')
                );

            case 'year':
                $start = $startInput->copy()->startOfYear();
                $end   = $endInput->copy()->endOfYear();

                if($data['display'] == 'year'){
                    $totalPeriods = $start->diffInYears($end) + 1;

                    return $this->generateChartData(
                        'YEAR',
                        $start,
                        $end,
                        $totalPeriods,
                        labelCallback: fn ($d) => $d->format('Y')
                    );
                }
                $totalPeriods = $start->diffInMonths($end) + 1;

                return $this->generateChartData(
                    'MONTH',
                    $start,
                    $end,
                    $totalPeriods,
                    labelCallback: fn ($d) => $d->format('m/Y')
                );

            default:
                throw new \InvalidArgumentException('Invalid type:' .$data['type'] );
        }
    }
    private function generateChartData(string $keyType, $start, $end, int $totalPeriods, callable $labelCallback): array
    {
        // Chọn format key chuẩn để tránh duplicate
        $formatKey = match($keyType) {
            'HOUR'  => 'Y-m-d H',
            'DAY'   => 'Y-m-d',
            'MONTH' => 'Y-m',
            'YEAR'  => 'Y',
            default => 'Y-m-d',
        };
        $mysqlFormat = match ($keyType) {
            'HOUR'  => '%Y-%m-%d %H',
            'DAY'   => '%Y-%m-%d',
            'MONTH' => '%Y-%m',
            'YEAR'  => '%Y',
        };
        // Query DB và keyBy theo định dạng chuẩn
        $rows = Order::whereBetween('created_at', [$start->copy()->utc(),
            $end->copy()->utc(),])
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->selectRaw("
                DATE_FORMAT(created_at, '{$mysqlFormat}') as period,
                SUM(total) as value
                ")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');
        $data = [];
        $cursor = $start->copy();

        for ($i = 0; $i < $totalPeriods; $i++) {

            $index = $cursor->format($formatKey);

            $data[] = [
                'date'  => $labelCallback($cursor),
                'value' => isset($rows[$index]) ? (float) $rows[$index]->value : 0.0,
            ];

            match ($keyType) {
                'HOUR'  => $cursor->addHour(),
                'DAY'   => $cursor->addDay(),
                'MONTH' => $cursor->addMonth(),
                'YEAR'  => $cursor->addYear(),
            };
        }
        return $data;
    }
}

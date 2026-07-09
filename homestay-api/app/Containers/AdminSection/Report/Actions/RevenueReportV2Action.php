<?php

namespace App\Containers\AdminSection\Report\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Actions\Action as ParentAction;
use DB;

final class RevenueReportV2Action extends ParentAction
{
    public function run($data)
    {
        $month = $data['month'];
        $year = $data['year'];

        $gross_gmv = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->select(DB::raw('SUM(total + COALESCE(discount_amount, 0) + buffer_price) as total_revenue'))
            ->value('total_revenue');

        $customer_paid = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->sum('total');

        $voucher_amount = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->whereNotNull('voucher_id')
            ->sum('discount_amount');

        $commission_amount = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->selectRaw('SUM((total + COALESCE(discount_amount, 0)) * commission_percent / 100) as total_commission')
            ->value('total_commission');
        $buffer_amount = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->sum('buffer_price');

        $hostPrice = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->select(DB::raw('SUM(total + discount_amount - buffer_price) as total_revenue'))
            ->value('total_revenue');

        $host_payout = $hostPrice - $commission_amount;

        $gorio_actual_revenue = $commission_amount - $voucher_amount + $buffer_amount;

        return [
            'gross_gmv' => $gross_gmv,
            'customer_paid' => $customer_paid,
            'voucher_amount' => $voucher_amount,
            'commission_amount' => $commission_amount,
            'buffer_amount' => $buffer_amount,
            'host_payout' => $host_payout,
            'gorio_actual_revenue' => $gorio_actual_revenue,
        ];

    }
}

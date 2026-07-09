<?php

namespace App\Containers\AdminSection\Report\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;

final class RevenueReportAction extends ParentAction
{
    public function run($data)
    {
        $month = $data['month'];
        $year = $data['year'];


        //Tổng doanh thu đã bao gồm voucher
        $totalRevenueExcludingVoucher = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->select(DB::raw('SUM(total + discount_amount) as total_revenue'))
            ->value('total_revenue');

        $totalOrders = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->count();

        $totalBuffer = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->sum('buffer_price');

        $avgUnitRevenue = $totalRevenueExcludingVoucher/($totalOrders > 0 ? $totalOrders : 1);

        $commissionOnTotalRevenue = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->selectRaw('
                 SUM((commission_percent / 100.0) * (total + COALESCE(discount_amount, 0))) as total_commission
             ')
            ->value('total_commission');


        //Tổng doanh thu không dùng voucher
        $totalRevenueWithoutVoucher = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->whereNull('voucher_id')
            ->select(DB::raw('SUM(total) as total_revenue'))
            ->value('total_revenue');

        $totalOrdersWithoutVoucher = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->whereNull('voucher_id')
            ->count();

        $avgUnitRevenueWithoutVoucher = $totalRevenueWithoutVoucher/($totalOrdersWithoutVoucher > 0  ? $totalOrdersWithoutVoucher: 1);

        $commissionOnTotalRevenueWithoutVoucher = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->whereNull('voucher_id')
            ->selectRaw('
                 SUM((commission_percent / 100.0) * total) as total_commission
             ')
            ->value('total_commission');

        //Tổng doanh thu dùng voucher
        $totalRevenueWithVoucher = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->whereNotNull('voucher_id')
            ->select(DB::raw('SUM(total + discount_amount) as total_revenue'))
            ->value('total_revenue');

        $totalOrdersWithVoucher = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->whereNotNull('voucher_id')
            ->count();

        $avgUnitRevenueWithVoucher = $totalRevenueWithVoucher/($totalOrdersWithVoucher > 0 ? $totalOrdersWithVoucher : 1);

        $commissionOnTotalRevenueWithVoucher = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->whereNotNull('voucher_id')
            ->selectRaw('
                 SUM((commission_percent / 100.0) * (total + COALESCE(discount_amount, 0))) as total_commission
             ')
            ->value('total_commission');

        //Tổng tiền voucher đã sử dụng
        $totalVoucher = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', Order::STATUS_PAID)
            ->where('code', 'like', 'GR%')
            ->whereNotNull('voucher_id')
            ->select(DB::raw('SUM(discount_amount) as total_voucher'))
            ->value('total_voucher');

        //Lợi nhuận trên tổng doanh thu = Hoa hồng trên tổng doanh thu - Tổng tiền voucher đã áp dụng
        $profitOnTotalRevenue = $commissionOnTotalRevenue - $totalVoucher + $totalBuffer;

        //Lợi nhuận trên tổng doanh thu áp voucher = Hoa hồng trên tổng doanh thu áp voucher - Tổng tiền voucher đã áp dụng
        $profitOnTotalRevenueWithVoucher = $commissionOnTotalRevenueWithVoucher - $totalVoucher + $totalBuffer;


        return [
            'total_revenue_excluding_voucher' => $totalRevenueExcludingVoucher,
            'total_orders' => $totalOrders,
            'avg_unit_revenue' => $avgUnitRevenue,
            'commission_on_total_revenue' => $commissionOnTotalRevenue,
            'total_revenue_without_voucher' => $totalRevenueWithoutVoucher,
            'total_orders_without_voucher' => $totalOrdersWithoutVoucher,
            'avg_unit_revenue_without_voucher' => $avgUnitRevenueWithoutVoucher,
            'commission_on_total_revenue_without_voucher' => $commissionOnTotalRevenueWithoutVoucher,
            'total_revenue_with_voucher' => $totalRevenueWithVoucher,
            'total_orders_with_voucher' => $totalOrdersWithVoucher,
            'avg_unit_revenue_with_voucher' => $avgUnitRevenueWithVoucher,
            'commission_on_total_revenue_with_voucher' => $commissionOnTotalRevenueWithVoucher,
            'total_voucher' => $totalVoucher,
            'total_buffer' => $totalBuffer,
            'profit_on_total_revenue' => $profitOnTotalRevenue,
            'profit_on_total_revenue_with_voucher' => $profitOnTotalRevenueWithVoucher,
        ];
    }
}

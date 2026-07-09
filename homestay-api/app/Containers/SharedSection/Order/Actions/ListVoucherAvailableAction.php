<?php

namespace App\Containers\SharedSection\Order\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListVoucherAvailableAction extends ParentAction
{
    public function run($request, $userId)
    {
        $now = now();
        if(!empty($request['search'])){
            $vouchers = Voucher::where('is_active', true)
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->whereColumn('quantity', '>', 'used_count')
                ->where('code', $request['search'])
                ->get();
        }else{
            $vouchers = Voucher::where('is_active', true)
                ->where('type', Voucher::TYPE_VOUCHER_PUBLIC)
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->whereColumn('quantity', '>', 'used_count')
                ->get();
        }

        $result = [];

        foreach($vouchers as $voucher){
            $userUsedCount = Order::where('user_id', $userId)
                ->where('status', Order::STATUS_PAID)
                ->where('voucher_id', $voucher->id)
                ->count();
            if (is_null($voucher->usage_limit) || $userUsedCount < $voucher->usage_limit) {
                $result[] = $voucher;
            }
        }
        return collect($result);
    }
}

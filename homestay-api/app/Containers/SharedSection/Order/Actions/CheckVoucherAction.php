<?php

namespace App\Containers\SharedSection\Order\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class CheckVoucherAction extends ParentAction
{
    public function run($code)
    {
        $now = now();
        $voucher = Voucher::whereRaw('LOWER(code) = ?', [strtolower($code)])->first();

        if (!$voucher->is_active) {
            throw ValidationException::withMessages([
                'code' => [__('voucher.inactive')],
            ]);
        }

        if ($voucher->start_date > $now || $voucher->end_date < $now) {
            throw ValidationException::withMessages([
                'code' => [__('voucher.expired')],
            ]);
        }

        if ($voucher->used_count >= $voucher->quantity) {
            throw ValidationException::withMessages([
                'code' => [__('voucher.used_up')],
            ]);
        }

        $userId = Auth::id();
        if ($userId && $voucher->usage_limit !== null) {
            $userUsedCount = $voucher->orders()
                ->where('status', Order::STATUS_PAID)
                ->where('user_id', $userId)
                ->count();

            if ($userUsedCount >= $voucher->usage_limit) {
                throw ValidationException::withMessages([
                    'code' => [__('voucher.limit_reached')],
                ]);
            }
        }


        return $voucher;
    }
}

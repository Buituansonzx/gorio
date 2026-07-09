<?php

namespace App\Containers\AdminSection\Voucher\Actions;

use App\Containers\AppSection\Notification\Models\Notification;
use App\Containers\AppSection\Notification\Services\NotificationService;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Auth;

final class CreateVoucherAction extends ParentAction
{
    public function run($data)
    {
        $createByUserId = Auth::id();

        $voucher = Voucher::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'discount_type' => $data['discount_type'],
            'type' => $data['type'],
            'discount_value' => $data['discount_value'],
            'max_discount_amount' => $data['max_discount_amount'] ?: null,
            'min_order_amount' => $data['min_order_amount'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'quantity' => $data['quantity'],
            'usage_limit' => $data['usage_limit'] ?? null,
            'is_active' => $data['is_active'] === '' ? true : $data['is_active'],
            'created_by' => $createByUserId,
        ]);

        if(!empty($data['image'])){
            $file = request()->file('image');
            $dir = "voucher/image/{$voucher->id}";
            $path = $file->store($dir, 's3');
            if ($voucher) {
                $voucher->image_path = $path;
                $voucher->save();
            }
        }
        if($voucher->type == Voucher::TYPE_VOUCHER_PUBLIC){
            app(NotificationService::class)->createNotificationVoucher($voucher);
        }

        return $voucher;
    }
}

<?php

namespace App\Containers\AdminSection\Order\Actions;

use App\Containers\AppSection\Notification\Services\NotificationService;
use App\Containers\SharedSection\Order\Models\Order;
use App\Jobs\CancelOrderHongManageJob;
use App\Jobs\CreateOrderHongManageJob;
use App\Jobs\UpdateOrderHongManagerJob;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateOrderAction extends ParentAction
{
    public function run($orderId, $data)
    {
        $order = Order::findOrFail($orderId);
        $orderOld = Order::find($orderId);

        $oldData = [
            'room_code_old' => $orderOld->room?->roomCodeMapping?->hong_manage_room_code,
            'checkin_old' => $orderOld->check_in,
            'checkout_old' => $orderOld->check_out,
        ];

        $fillableData = array_intersect_key(
            $data,
            array_flip([
                'room_id',
                'guest_name',
                'guest_phone',
                'check_in',
                'check_out',
                'total',
                'status',
            ])
        );
        $oldStatus = $order->status;
        $order->fill($fillableData);
        $order->save();
       if(config('app.env') == 'production'){
            if($oldStatus !== Order::STATUS_PAID && $order->status === Order::STATUS_PAID){
                //Tạo đơn ở hóng manage
                CreateOrderHongManageJob::dispatch($order);
                // Gửi thông báo đặt phòng thành công
                app(NotificationService::class)->createNotificationBooking($order, 'vi');
            }else if($oldStatus === Order::STATUS_PAID && $order->status !== Order::STATUS_PAID){
                //Hủy đơn ở hóng manage
                CancelOrderHongManageJob::dispatch($order);
                //Xóa thông báo đặt phòng thành công
                app(NotificationService::class)->softDeleteNotificationForOrder($order->id, $order->user_id);
            }else{
                UpdateOrderHongManagerJob::dispatch($order,$oldData);
            }
       }
        if (
            array_key_exists('status', $fillableData) &&
            $oldStatus !== $order->status
        ) {
            $this->syncPaymentStatus($order);
        }

        return $order;
    }
    protected function syncPaymentStatus(Order $order): void
    {
        if (!$order->payment) {
            return;
        }

        $map = [
            'pending'   => 'pending',
            'paid'      => 'success',
            'cancelled' => 'failed',
            'expired'   => 'failed',
        ];

        if (!isset($map[$order->status])) {
            return;
        }

        $order->payment->status = $map[$order->status];
        $order->payment->save();
    }

}

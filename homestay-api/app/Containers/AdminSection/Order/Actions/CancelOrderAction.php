<?php

namespace App\Containers\AdminSection\Order\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CancelOrderAction extends ParentAction
{
    public function run($orderId)
    {
        $order = Order::find($orderId);
        if(!$order) {
            return null;
        }
        $order->status = 'cancelled';
        $order->save();
        return $order;
    }
}

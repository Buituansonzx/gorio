<?php

namespace App\Containers\ClientSection\Order\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetStatusPaymentAction extends ParentAction
{
    public function run($orderId)
    {
        $order = Order::find($orderId);
        return $order->payment->status;
    }
}

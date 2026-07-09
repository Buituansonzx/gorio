<?php

namespace App\Containers\AdminSection\Order\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DetailOrderAction extends ParentAction
{
    public function run($orderId)
    {
        $order = Order::find($orderId)->load(['room', 'user', 'voucher','room.specialOffers']);
        if (!$order) {
            throw new \Exception('Order not found');
        }
        return $order;
    }
}

<?php

namespace App\Containers\AdminSection\Order\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class OrderTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Order $order): array
    {
        $originalTotal = $order->total / (1 - $order->room->system_discount_percent / 100 - ($order->room->specialOffers->first()->last_minute_discount_percent ?? 0) / 100);
        $hostRevenue = $originalTotal - ($originalTotal * $order->commission_percent / 100);
        return [
            'type' => $order->getResourceKey(),
            'id' => $order->id,
            'room_id' => $order->room_id,
            'room_name' => $order->room->name,
            'total' => $order->total,
            'host_revenue' => $hostRevenue,
            'check_in' => $order->check_in,
            'check_out' => $order->check_out,
            'guest_name' => $order->guest_name,
            'guest_phone' => $order->guest_phone,
            'number_of_guests' => $order->number_of_guests,
            'name' => $order->user->name,
            'phone_number' => $order->user->phone_number,
            'status' => $order->status,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

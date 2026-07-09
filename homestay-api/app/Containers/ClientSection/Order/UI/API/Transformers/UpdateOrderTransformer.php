<?php

namespace App\Containers\ClientSection\Order\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class UpdateOrderTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Order $order): array
    {
        return [
            'type' => $order->getResourceKey(),
            'id' => $order->id,
            'user_id' => $order->user_id,
            'room_id' => $order->room_id,
            'check_in' => $order->check_in,
            'check_out' => $order->check_out,
            'number_of_guests' => $order->number_of_guests,
            'guest_name' => $order->guest_name,
            'guest_phone' => $order->guest_phone,
            'price' => $order->price,
            'note' => $order->note,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
        ];
    }
}

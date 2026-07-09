<?php

namespace App\Containers\AdminSection\Timeline\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class EventTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Order $order): array
    {
        return [
            'id' => $order->id,
            'room_id' => $order->room_id,
            'guest_name' => $order->guest_name,
            'guest_phone' => $order->guest_phone,
            'check_in' => $order->check_in,
            'check_out' => $order->check_out,
            'total' => $order->total,
            'status' => $order->status,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

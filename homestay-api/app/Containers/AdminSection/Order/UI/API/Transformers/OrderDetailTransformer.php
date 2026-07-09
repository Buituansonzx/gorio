<?php

namespace App\Containers\AdminSection\Order\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class OrderDetailTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Order $order): array
    {
        $originalTotal = $order->total / (1 - $order->room->system_discount_percent / 100 - ($order->room->specialOffers->first()->last_minute_discount_percent ?? 0) / 100);
        $hostRevenue = $originalTotal - ($originalTotal * $order->commission_percent / 100);
        $websiteRevenue = $order->total -  $hostRevenue;
        return [
            'type' => $order->getResourceKey(),
            'id' => $order->id,
            'room_name' => $order->room->name,
            'room_code' => $order->room->code,
            'total' => $order->total,
            'host_revenue' => $hostRevenue,
            'website_revenue' => $websiteRevenue,
            'number_of_guests' => $order->number_of_guests,
            'check_in' => $order->check_in,
            'check_out' => $order->check_out,
            'user_name' => $order->user->name,
            'user_email' => $order->user->email,
            'user_phone_number' => $order->user->phone_number,
            'status' => $order->status,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
            'review' => $order->review ? [
                'user_name' => $order->review->user->name,
                'rating' => $order->review->rating,
                'content' => $order->review->content,
                'created_at' => $order->review->created_at->format('Y-m-d H:i:s'),
            ] : null,
        ];
    }
}

<?php

namespace App\Containers\MobileSection\Profile\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use App\Ship\Services\ImageService;

final class TripsTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Order $order): array
    {
        $firstImage = $order->room?->medias->first();
        $imageUrl = null;
        if($firstImage){
            $imageUrl =
                app(ImageService::class)->toMobilePayload($firstImage)['img']['src'];
        }
        return [
            'type' => $order->getResourceKey(),
            'id' => $order->id,
            'check_in' => $order->check_in,
            'check_out' => $order->check_out,
            'host_name' => $order->room->host->user->name,
            'brand_name' => $order->room->host->business_name,
            'room' => $order->room ? [
                'district' => $order->room->district->name,
                'province' => $order->room->district->province->name,
                'address' => $order->room->address,
                'image' =>$imageUrl
            ] : null,
        ];
    }
}

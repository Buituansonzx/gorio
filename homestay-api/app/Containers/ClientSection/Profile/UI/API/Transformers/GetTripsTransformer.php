<?php

namespace App\Containers\ClientSection\Profile\UI\API\Transformers;

use App\Containers\ClientSection\Room\UI\API\Transformers\RoomImageTransformer;
use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\CheckinMethod;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use App\Ship\Services\ImageService;

final class GetTripsTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
        'image',
    ];

    protected array $availableIncludes = [];

    public function transform(Order $order): array
    {
        return [
            'type' => $order->getResourceKey(),
            'id' => $order->id,
            'check_in' => $order->check_in,
            'check_out' => $order->check_out,
            'room' => $order->room ? [
                'district' => $order->room->district->name,
                'province' => $order->room->district->province->name,
                'address' => $order->room->address,
                'guide_video' => $order->room->house && $order->room->house->guide_video
                    ? app(S3Helper::class)->getFileUrl($order->room->house->guide_video)
                    : null,
            ] : null,
            'host_name' => $order->room->host->user->name,
            'brand_name' => $order->room->host->business_name,
        ];
    }

    public function includeImage(Order $order)
    {
        if ($order->room->medias && $order->room->medias->isNotEmpty()) {
            $imagesArray = $order->room->medias->map(function ($image) {
                return [
                    'image_url' => app(ImageService::class)->toResponsivePayload($image,'(max-width: 768px) 100vw, 960px','content-1440')['sources'],
                ];
            })->toArray();

            return $this->primitive($imagesArray);
        }
        return $this->primitive([]);
    }
}

<?php

namespace App\Containers\ClientSection\Profile\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use App\Ship\Services\ImageService;

final class GetPassTripsTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
//        'policy'
    ];

    protected array $availableIncludes = [];

    public function transform(Order $order): array
    {
        $firstImage = $order->room?->medias->first();
        $imageUrl = null;
        if($firstImage){
            $imageUrl =
                app(ImageService::class)->toResponsivePayload($firstImage,'(max-width: 768px) 100vw, 960px','content-1440')['sources'];

        }
        return array(
            'type' => $order->getResourceKey(),
            'id' => $order->id,
            'check_in' => $order->check_in,
            'check_out' => $order->check_out,
            'host_name' => $order->room->host->user->name,
            'brand_name' => $order->room->host->business_name,
            'room' => $order->room ? array(
                'district' => $order->room->district->name,
                'province' => $order->room->district->province->name,
                'address' => $order->room->address,
                'image_url' => $imageUrl,
                'guide_video' => $order->room->house && $order->room->house->guide_video
                    ? app(S3Helper::class)->getFileUrl($order->room->house->guide_video)
                    : null,
            ) : null,
        );
    }
}

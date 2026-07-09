<?php

namespace App\Containers\MobileSection\Profile\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use App\Ship\Services\ImageService;

final class PassTripsTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Order $order): array
    {
        $firstImage = $order->room?->medias->first();
        $imageUrl = null;
        if($firstImage){
            $imageUrl =
                app(ImageService::class)->toMobilePayload($firstImage,'(max-width: 768px) 100vw, 960px','content-1440')['img']['src'];

        }
        return [
            'type' => $order->getResourceKey(),
            'id' => $order->id,
            'check_in' => $order->check_in,
            'check_out' => $order->check_out,
            'district_name' => $order->room->district->getTranslation('name', request()->header('Accept-Language')),
            'host_name' => $order->room->host->user->name,
            'brand_name' => $order->room->host->business_name,
            'image' => $imageUrl,
        ];
    }
}

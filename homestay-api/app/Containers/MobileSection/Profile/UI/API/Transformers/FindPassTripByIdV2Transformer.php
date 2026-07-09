<?php

namespace App\Containers\MobileSection\Profile\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\CheckinMethod;
use App\Containers\SharedSection\Room\Models\RoomPolicy;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use App\Ship\Services\ImageService;
use Carbon\Carbon;

final class FindPassTripByIdV2Transformer extends ParentTransformer
{
    protected array $defaultIncludes = ['checkinMethod'];

    protected array $availableIncludes = [];

    public function transform(Order $order): array
    {
        $imagesArray = $order->room->medias->map(function ($image) {
            return [
                'image_url' => app(ImageService::class)->toMobilePayload($image,'(max-width: 768px) 100vw, 960px','content-1440')['img']['src'],
            ];
        })->toArray();
        $houseRulesArray = $order->room->houseRule->map(function ($houseRule) use ($order) {
            return [
                'name' => $houseRule->getTranslation('name', request()->header('Accept-Language')),
                'value' => $houseRule->pivot->value,
            ];
        })->toArray();
        $policyArray = $order->room->roomPolicy->map(function (RoomPolicy $policy) use ($order) {
            return [
                'policy_type' => $policy->policy_type,
                'content' => $policy->content,
            ];
        })->first();

        return [
            'type' => $order->getResourceKey(),
            'id' => $order->id,
            'code' => $order->code,
            'check_in' => $order->check_in,
            'check_out' => $order->check_out,
            'number_of_guests' => $order->number_of_guests,
            'guest_name' => $order->guest_name,
            'guest_phone' => $order->guest_phone,
            'total' => $order->total,
            'room' => [
                'id' => $order->room->id,
                'name' => $order->room->name,
                'title' => $order->room->title,
                'address' => $order->room->address,
                'latitude' => $order->room->latitude,
                'longitude' => $order->room->longitude,
                'image' => $imagesArray
            ],
            'rulesAndPolicies' => [
                'rules' => $houseRulesArray,
                'cancellation_policy' => $policyArray,
            ],
            'host' => [
                'id' => $order->room->host->id,
                'name' => $order->room->host->user->name,
                'brand_name' => $order->room->host->business_name,
                'phone' => $order->room->host->user->phone_number,
                'description' => $order->room->host->description,
            ]
        ];
    }

    public  function includeCheckinMethod(Order $order)
    {
        $checkoutTime = Carbon::parse($order->check_out);

        if (!$checkoutTime) {
            return null;
        }

        $minutesDiff = $checkoutTime->diffInMinutes(now(), false);

        if ($minutesDiff <= CheckinMethod::LIMIT_MINUTES_AFTER_CHECKOUT) {
            return $this->collection(
                $order->room->roomCheckinInstruction,
                new CheckinInstructionTransformer()
            );
        }

        return $this->item([
            'limit_minutes_after_checkout' => CheckinMethod::LIMIT_MINUTES_AFTER_CHECKOUT
        ], function ($data) {
            return $data;
        });
    }
}

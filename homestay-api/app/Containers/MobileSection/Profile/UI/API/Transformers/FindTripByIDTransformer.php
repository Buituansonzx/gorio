<?php

namespace App\Containers\MobileSection\Profile\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\CheckinMethod;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use App\Ship\Services\ImageService;

final class FindTripByIDTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [
        'image',
        'checkinMethod',
        'parkingRule',
        'checkoutInstruction',
        'houseRule',
        'policy'
    ];

    protected array $availableIncludes = [];

    public function transform(Order $order): array
    {
        return [
            'type' => $order->getResourceKey(),
            'id' => $order->id,
            'code' => $order->code,
            'check_in' => $order->check_in,
            'check_out' => $order->check_out,
            'total' => $order->total,
            'number_of_guests' => $order->number_of_guests,
            'guest_name' => $order->guest_name,
            'guest_phone' => $order->guest_phone,
            'room' => $order->room ? [
                'id' => $order->room->id,
                'name' => $order->room->name,
                'title' => $order->room->title,
                'district' => $order->room->district->name,
                'province' => $order->room->district->province->name,
                'address' => $order->room->address,
                'latitude' => $order->room->latitude,
                'longitude' => $order->room->longitude,
            ] : null,
            'host' => $order->room ? [
                'id' => $order->room->host->id,
                'name' => $order->room->host->user->name,
                'brand_name' => $order->room->host->business_name,
                'phone' => $order->room->host->user->phone_number,
                'description' => $order->room->host->description,
            ] : null,
        ];
    }
    public  function includeCheckinMethod(Order $order)
    {
        $checkinTime = $order->check_in;

        if (!$checkinTime) {
            return null;
        }

        $hoursDiff = now()->diffInHours($checkinTime, false);

        // chỉ khi còn <= 2 tiếng mới trả ra
        if ($hoursDiff <= CheckinMethod::LIMIT_HOURS_BEFORE_CHECKIN) {
            return $this->collection(
                $order->room->roomCheckinInstruction,
                new CheckinInstructionTransformer()
            );
        }

        return $this->item([
            'limit_hours_before_checkin' => CheckinMethod::LIMIT_HOURS_BEFORE_CHECKIN
        ], function ($data) {
            return $data;
        });
    }

    public function includeImage(Order $order)
    {

        if ($order->room->medias && $order->room->medias->isNotEmpty()) {
            $imagesArray = $order->room->medias->map(function ($image) {
                return [
                    'image_url' => app(ImageService::class)->toMobilePayload($image)['img']['src'],
                ];
            })->toArray();

            return $this->primitive($imagesArray);
        }
        return $this->primitive([]);
    }

    public function includeCheckoutInstruction(Order $order)
    {
        return $this->collection($order->room->checkoutInstructionType, new CheckoutInstructionTransformer());
    }
    public function includePolicy(Order $order)
    {
        if ($order->room->roomPolicy && $order->room->roomPolicy->isNotEmpty()) {
            $roomPolicyArray = $order->room->roomPolicy->map(function ($policy) {
                return [
                    'policy_type' => $policy->policy_type,
                    'content' => $policy->content,
                ];
            })->toArray();

            return $this->primitive($roomPolicyArray);
        }
        return $this->primitive([]);
    }
    public function includeHouseRule(Order $order)
    {
        if ($order->room->houseRule && $order->room->houseRule->isNotEmpty()) {
            $houseRulesArray = $order->room->houseRule->map(function ($houseRule) use ($order) {
                return [
                    'name' => $houseRule->getTranslation('name', request()->header('Accept-Language')),
                    'value' => $houseRule->pivot->value,
                ];
            })->toArray();

            return $this->primitive($houseRulesArray);
        }
        return $this->primitive([]);
    }
    public function includeParkingRule(Order $order)
    {
        if ($order->room->parkingRules && $order->room->parkingRules->isNotEmpty()) {
            $parkingRulesArray = $order->room->parkingRules->map(function ($parkingRule) {
                return [
                    'type' => $parkingRule->getResourceKey(),
                    'is_free' => $parkingRule->is_free,
                    'price' => $parkingRule->price,
                    'currency' => $parkingRule->currency,
                    'location' => $parkingRule->location,
                    'distance_meters' => $parkingRule->distance_meters,
                    'description' => $parkingRule->description
                ];
            })->toArray();

            return $this->primitive($parkingRulesArray);
        }
        return $this->primitive([]);
    }
}

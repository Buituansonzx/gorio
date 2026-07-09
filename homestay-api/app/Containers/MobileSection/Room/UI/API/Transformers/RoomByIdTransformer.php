<?php

namespace App\Containers\MobileSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Profile\UI\API\Transformers\ReviewTransformer;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use App\Ship\Services\ImageService;
use App\Ship\Traits\RoomPriceCalcTrait;
use Carbon\Carbon;

final class RoomByIdTransformer extends ParentTransformer
{
    use RoomPriceCalcTrait;

    protected array $defaultIncludes = [
        'roomType',
        'roomAccessType',
        'images',
        'attributes',
        'surroundingFacilities',
        'pricingPolicies',
        'views',
//        'weekdayPrices',
        'review',
        'discountPolicies',
        'houseRules',
        'roomPolicy',
        'amenities',
        'highlightAmenities',
        'parkingRule',
        'fixedCheckTime',
        'comboPricing',];

    protected array $availableIncludes = [];

    public function transform(Room $room): array
    {
        $language = request()->header('Accept-Language')?? 'vi';
        $reviews = $room->reviews;   //Lấy ra tất cả review của phòng
        $host = $room->host;
        $allReviews = $host->rooms->flatMap->reviews; //Lấy ra tất cả review của host
        $avgRating = round($allReviews->avg('rating'), 2);
        $user = auth()->user();
        $isFavorite = false;
        if ($user) {
            $isFavorite = $room->favoritedBy()
                ->where('user_id', $user->id)
                ->wherePivot('is_favorite', true)
                ->exists();
        }
        $isLovedByEveryone = false;
        if(round($reviews->avg('rating'), 1) >= 4.7 ){
            $isLovedByEveryone = true;
        }

        $maxVoucherAmount = $this->getMaxGlobalVoucherDiscount($user);


        return [
            'type' => $room->getResourceKey(),
            'id' => $room->id,
            'code' => $room->code,
            'max_voucher_amount' => $maxVoucherAmount,
            'house_name' => $room->house?->name,
            'is_favorite' => $isFavorite,
            'is_loved_by_everyone' => $isLovedByEveryone,
            'district_name' => $room->district->getTranslation('name', $language),
            'province_name' => $room->district->province->getTranslation('name', $language),
            'name' => $room->name,
            'title' => $room->title,
            'room_type' => $room->room_type,
            'address' => $room->address,
            'latitude' => $room->latitude,
            'longitude' => $room->longitude,
            'room_type_id' => $room->room_type_id,
            'access_type' => $room->accessType->getTranslation('name', $language),
            'price' => $room->fixedCheckTimeStd->first()?->pivot?->price,
            'avg_rating' => round($reviews->avg('rating'), 2),
            'count_rating' => $reviews->count('rating'),
            'max_guests' => $room->max_guests,
            'base_guests' => $room->base_guests,
            'num_beds' => $room->num_beds,
            'num_bathrooms' => $room->num_bathrooms,
            'area_sqm' => $room->area_sqm,
            'description' => $room->description,
            'host' => [
                'id' => $room->host->id,
                'name' => $room->host->user->name,
                'brand_name' => $room->host->business_name,
                'avatar' =>S3Helper::getS3ImageUrl($room->host->avatar),
                'avg_rating' =>$avgRating,
                'count_rating' => count($allReviews),
                'started_at' => $room->host->created_at,
            ],
            'review_statistics' => json_decode($room->data),
            'features' => [
                'checkin/checkout_name' => $room->checkinMethods->first()->getTranslation('name', $language),
                'checkin/checkout_description' => $room->checkinMethods->first()->getTranslation('description', $language),
                'is_by_hour' => $room->hourlyPricing->isNotEmpty() ? 'Cho phép đặt theo giờ' : null,
                'fixed_time_std_name' => $room->fixedCheckTimeStd->first()?->getTranslation('name', $language),
                'fixed_time_std_description' => $room->fixedCheckTimeStd->first()?->getTranslation('description', $language),
            ],
            'hourly_pricing' => [
                'min_hours' => optional($room->hourlyPricing?->first())->min_hours,
                'min_hours_price' => optional($room->hourlyPricing?->first())->min_hours_price,
                'next_hour_price' => optional($room->hourlyPricing?->first())->next_hour_price,
                'extra_adult_price' => optional($room->pricingPolicy?->first())->extra_adult_price,
            ],
            'is_active' => $room->is_active,
        ];
    }

    public function includeRoomType(Room $room)
    {
        $locale = request()->header('Accept-Language') ?? app()->getLocale() ?? 'en';
        if ($room->roomType || $room->roomType->NotEmpty()) {
            return $this->primitive([
                'object' => $room->roomType->getResourceKey(),
                'id' => $room->roomType->id,
                'name' => $room->roomType->getTranslation('name', $locale),
                'description' => $room->roomType->getTranslation('description', $locale),
            ]);
        }
    }
//    public function includeHost(Room $room)
//    {
//        return $this->item($room->host, new GetProfileHostTransformer());
//    }
    public function includeRoomAccessType(Room $room)
    {
//        return $this->item($room->accessType, new RoomAccessTypeTransformer());

        if ($room->accessType) {
            return $this->primitive([
                'object' => $room->accessType->getResourceKey(),
                'id' => $room->accessType->id,
                'name' => $room->accessType->getTranslation('name', request()->header('Accept-Language')),
                'description' => $room->accessType->getTranslation('description', request()->header('Accept-Language')),
            ]);
        }
    }

    public function includeImages(Room $room)
    {
        if ($room->medias && $room->medias->isNotEmpty()) {
            $imagesArray = $room->medias->map(function ($image) {
                return [
                    'object' => $image->getResourceKey(),
                    'id' => $image->id,
                    'room_image_area_group_id' => $image->room_image_area_group_id,
                    'image_url' => app(ImageService::class)->toMobilePayload($image)['img']['src'],
                    'is_cover' => $image->is_cover,
                ];
            })->toArray();

            return $this->primitive($imagesArray);
        }
        return $this->primitive([]);
    }

    public function includeAttributes(Room $room)
    {
        if ($room->attributes && $room->attributes->isNotEmpty()) {
            $attributesArray = $room->attributes->map(function ($attribute) {
                return [
                    'object' => $attribute->getResourceKey(),
                    'id' => $attribute->id,
                    'name' => $attribute->getTranslation('name', request()->header('Accept-Language')),
                    'quantity' => $attribute->pivot->quantity,
                ];
            })->toArray();

            return $this->primitive($attributesArray);
        }
        return $this->primitive([]);
    }

    public function includeSurroundingFacilities(Room $room)
    {

        if ($room->surroundingFacilities && $room->surroundingFacilities->isNotEmpty()) {
            $facilitiesArray = $room->surroundingFacilities->map(function ($facility) {
                return [
                    'object' => $facility->getResourceKey(),
                    'id' => $facility->id,
                    'name' => $facility->getTranslation('name', request()->header('Accept-Language')),
                ];
            })->toArray();

            return $this->primitive($facilitiesArray);
        }
        return $this->primitive([]);
    }

    public function includePricingPolicies(Room $room)
    {
        if ($room->pricingPolicy) {
            $pricingPolicy = $room->pricingPolicy;
            $data = [
                'object' => $pricingPolicy->getResourceKey(),
                'id' => $pricingPolicy->id,
                'extra_adult_price' => $pricingPolicy->extra_adult_price,
                'extra_child_price' => $pricingPolicy->extra_child_price,
                'extra_hour_price' => $pricingPolicy->extra_hour_price,
                'cleaning_fee' => $pricingPolicy->cleaning_fee,
                'deposit_amount' => $pricingPolicy->deposit_amount,
            ];
            return $this->primitive($data);
        }

        return null;
    }

//    public function includeWeekdayPrices(Room $room)
//    {
//        return $this->collection($room->weekdayPrices, new RoomWeekdayPriceTransformer());
//    }

    public function includeDiscountPolicies(Room $room)
    {
        if ($room->discountPolicies && $room->discountPolicies->isNotEmpty()) {
            $discountPoliciesArray = $room->discountPolicies->map(function ($roomDiscountPolicy) {
                return [
                    'object' => $roomDiscountPolicy->getResourceKey(),
                    'id' => $roomDiscountPolicy->id,
                    'discount_type' => $roomDiscountPolicy->discount_type,
                    'discount_value' => $roomDiscountPolicy->discount_value,
                    'min_stay_days' => $roomDiscountPolicy->min_stay_days,
                    'max_stay_days' => $roomDiscountPolicy->max_stay_days,
                    'start_date' => $roomDiscountPolicy->start_date?->toDateString(),
                    'end_date' => $roomDiscountPolicy->end_date?->toDateString(),
                    'is_active' => $roomDiscountPolicy->is_active,
                    'created_at' => $roomDiscountPolicy->created_at?->toISOString(),
                    'updated_at' => $roomDiscountPolicy->updated_at?->toISOString(),
                ];
            })->toArray();

            return $this->primitive($discountPoliciesArray);
        }
        return $this->primitive([]);
    }

    public function includeHouseRules(Room $room)
    {
        if ($room->houseRule && $room->houseRule->isNotEmpty()) {
            $houseRulesArray = $room->houseRule->map(function ($houseRule) {
                return [
                    'type' => $houseRule->getResourceKey(),
                    'id' => $houseRule->id,
                    'name' => $houseRule->getTranslation('name', request()->header('Accept-Language')),
                    'type_rule' => $houseRule->type,
                    'value' => $houseRule->pivot->value,
                ];
            })->toArray();

            return $this->primitive($houseRulesArray);
        }
        return $this->primitive([]);
    }

    public function includeRoomPolicy(Room $room)
    {
        if ($room->roomPolicy && $room->roomPolicy->isNotEmpty()) {
            $roomPolicyArray = $room->roomPolicy->map(function ($roomPolicy) {
                return [
                    'type' => $roomPolicy->getResourceKey(),
                    'policy_type' => $roomPolicy->policy_type,
                    'content' => $roomPolicy->content,
                ];
            })->toArray();

            return $this->primitive($roomPolicyArray);
        }
        return $this->primitive([]);
    }

    public function includeAmenities(Room $room)
    {
        if ($room->amenities && $room->amenities->isNotEmpty()) {
            $amenitiesArray = $room->amenities->map(function ($amenity) {
                return [
                    'type' => $amenity->getResourceKey(),
                    'id' => $amenity->id,
                    'name' => $amenity->getTranslation('name', request()->header('Accept-Language')),
                    'is_basic' => $amenity->is_basic,
                    'icon' => asset($amenity->icon),
                    'group' => $amenity->group->getTranslation('name', request()->header('Accept-Language'))
                ];
            })->toArray();

            return $this->primitive($amenitiesArray);
        }
        return $this->primitive([]);
    }

    public function includeHighlightAmenities(Room $room)
    {
        if ($room->highlightAmenities && $room->highlightAmenities->isNotEmpty()) {
            $highlightAmenitiesArray = $room->highlightAmenities->map(function ($roomHighlightAmenity) {
                return [
                    'type' => $roomHighlightAmenity->getResourceKey(),
                    'id' => $roomHighlightAmenity->amenity->id,
                    'name' => $roomHighlightAmenity->amenity->getTranslation('name', request()->header('Accept-Language')),
                    'service_type' => $roomHighlightAmenity->service_type,
                    'is_free' => $roomHighlightAmenity->is_free,
                    'price' => $roomHighlightAmenity->price,
                    'unit' => $roomHighlightAmenity->unit,
                    'status' => $roomHighlightAmenity->status,
                    'icon' => asset($roomHighlightAmenity->amenity->icon),
                    'images' => $roomHighlightAmenity->images?->map(function ($image) {
                            return [
                                'id' => $image->id,
                                'highlight_amenity_id' => $image->highlight_amenity_id,
                                'image_url' => app(ImageService::class)->toMobilePayload($image)['img']['src'],
                            ];
                        })->toArray() ?? []
                ];
            })->toArray();

            return $this->primitive($highlightAmenitiesArray);
        }
        return $this->primitive([]);
    }

    public function includeParkingRule(Room $room)
    {
        if ($room->parkingRules && $room->parkingRules->isNotEmpty()) {
            $parkingRulesArray = $room->parkingRules->map(function ($parkingRule) {
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

    public function includeFixedCheckTime(Room $room)
    {
        if ($room->fixedCheckTime && $room->fixedCheckTime->isNotEmpty()) {
            $user = auth()->user();
            $maxVoucherAmount = $this->getMaxGlobalVoucherDiscount($user);
            $hasVoucher = $maxVoucherAmount > 0;

            $fixedCheckTimesArray = $room->fixedCheckTime->map(function ($fixedCheckTime) use ($maxVoucherAmount, $hasVoucher) {
                $checkInDate = Carbon::parse(request()->check_in_time)->toDateString();
                $checkOutDate = $checkInDate;
                if($fixedCheckTime->is_overnight){
                    $checkOutDate = Carbon::parse(request()->check_in_time)->addDay()->toDateString();
                }
                $dayOfWeek = Carbon::parse($checkInDate)->dayOfWeek;
                $dayPriceColumn = match ($dayOfWeek) {
                    0 => 'sun_price',
                    1 => 'mon_price',
                    2 => 'tue_price',
                    3 => 'wed_price',
                    4 => 'thu_price',
                    5 => 'fri_price',
                    6 => 'sat_price',
                    default => 'price',
                };
                $price = $fixedCheckTime->pivot->{$dayPriceColumn} ?? $fixedCheckTime->pivot->price;

                // Tính buffer theo ngày trong tuần
                $dayBufferColumn = match ($dayOfWeek) {
                    0 => 'sun_buffer_price',
                    1 => 'mon_buffer_price',
                    2 => 'tue_buffer_price',
                    3 => 'wed_buffer_price',
                    4 => 'thu_buffer_price',
                    5 => 'fri_buffer_price',
                    6 => 'sat_buffer_price',
                    default => 'buffer_price',
                };
                $buffer = $hasVoucher ? ($fixedCheckTime->pivot->$dayBufferColumn ?? 0) : 0;
                $finalPrice = $price - $maxVoucherAmount + $buffer;

                return [
                    'type' => $fixedCheckTime->getResourceKey(),
                    'id' => $fixedCheckTime->id,
                    'name' => $fixedCheckTime->getTranslation('name', request()->header('Accept-Language')),
                    'price' => $finalPrice,
                    'old_price' => $price,
                    'start_time' => $fixedCheckTime->start_time,
                    'end_time' => $fixedCheckTime->end_time,
                    'check_in_date' => $checkInDate,
                    'check_out_date' => $checkOutDate,
                    'is_overnight' => $fixedCheckTime->is_overnight,
                    'description' => $fixedCheckTime->getTranslation('description', request()->header('Accept-Language')),
                ];
            })->toArray();

            return $this->primitive($fixedCheckTimesArray);
        }
        return $this->primitive([]);
    }

    public function includeComboPricing(Room $room)
    {
        if ($room->comboPricing && $room->comboPricing->isNotEmpty()) {
            $user = auth()->user();
            $maxVoucherAmount = $this->getMaxGlobalVoucherDiscount($user);
            $hasVoucher = $maxVoucherAmount > 0;

            $comboPricingArray = $room->comboPricing->map(function ($roomComboPricing) use ($maxVoucherAmount, $hasVoucher) {
                $checkInDate = Carbon::parse(request()->check_in_time)->toDateString();
                $dayOfWeek = Carbon::parse($checkInDate)->dayOfWeek;
                $dayPriceColumn = match ($dayOfWeek) {
                    0 => 'sun_price',
                    1 => 'mon_price',
                    2 => 'tue_price',
                    3 => 'wed_price',
                    4 => 'thu_price',
                    5 => 'fri_price',
                    6 => 'sat_price',
                    default => 'price',
                };
                $price = $roomComboPricing->{$dayPriceColumn} ?? $roomComboPricing->price;

                // Tính buffer theo ngày trong tuần
                $dayBufferColumn = match ($dayOfWeek) {
                    0 => 'sun_buffer_price',
                    1 => 'mon_buffer_price',
                    2 => 'tue_buffer_price',
                    3 => 'wed_buffer_price',
                    4 => 'thu_buffer_price',
                    5 => 'fri_buffer_price',
                    6 => 'sat_buffer_price',
                    default => 'buffer_price',
                };
                $buffer = $hasVoucher ? ($roomComboPricing->$dayBufferColumn ?? 0) : 0;
                $finalPrice = (float)$price - $maxVoucherAmount + $buffer;

                return [
                    'type' => $roomComboPricing->getResourceKey(),
                    'id' => $roomComboPricing->id,
                    'start_time' => $roomComboPricing->start_time,
                    'end_time' => $roomComboPricing->end_time,
                    'date' => $checkInDate,
                    'price' => $finalPrice,
                    'old_price' => $price,
                ];
            })->toArray();

            return $this->primitive($comboPricingArray);
        }
        return $this->primitive([]);
    }

    public function includeReview(Room $room)
    {
        return $this->collection($room->reviews, new ReviewTransformer());
    }

    public function includeViews(Room $room)
    {
        if ($room->views && $room->views->isNotEmpty()) {
            $viewArray = $room->views->map(function ($view) {
                return [
                    'type' => $view->getResourceKey(),
                    'id' => $view->id,
                    'name' => $view->getTranslation('name', request()->header('Accept-Language')),
                    'icon' => asset($view->icon_url)
                ];
            })->toArray();

            return $this->primitive($viewArray);
        }
        return $this->primitive([]);
    }
}

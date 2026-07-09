<?php

namespace App\Containers\MobileSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Order\Actions\ListVoucherAvailableAction;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Containers\SharedSection\Order\UI\API\Transformers\ListVoucherTransformer;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use App\Ship\Services\ImageService;
use App\Ship\Traits\RoomPriceCalcTrait;
use Carbon\Carbon;

final class RoomTransformer extends ParentTransformer
{
    use RoomPriceCalcTrait;

    protected array $defaultIncludes = [
        // 'roomType',
        'comboPricing',
        'images',
    ];

    protected array $availableIncludes = [
    ];

    public function transform(Room $room) : array
    {
        $checkInDate = request('check_in_time')
            ? Carbon::parse(request('check_in_time'))
            : Carbon::now();

        $checkOutDate = request('check_out_time')
            ? Carbon::parse(request('check_out_time'))
            : Carbon::now()->addDay();

        $maxGuests = request('max_guests') ? (int) request('max_guests') : 1;

        $dayOfWeek = $checkInDate->dayOfWeek;

        $dayPriceColumn = match ($dayOfWeek) {
            1 => 'mon_price',
            2 => 'tue_price',
            3 => 'wed_price',
            4 => 'thu_price',
            5 => 'fri_price',
            6 => 'sat_price',
            0 => 'sun_price',
        };
        $price = $room->fixedCheckTimeStd->first()?->pivot?->{$dayPriceColumn} ?? $room->fixedCheckTimeStd->first()?->pivot?->price ?? 0;
        $avgRating = $room->reviews->avg('rating') ?? 0;
        $countReviews = $room->reviews->count();
        $user = auth()->user();
        $isFavorite = (bool) ($room->is_favorite ?? false);
        $discountPercent =
            ((float) ($room->specialOffers?->first()->last_minute_discount_percent ?? 0))
            + ((float) ($room->system_discount_percent ?? 0));
        $priceAfterDiscount = $price - ($price * $discountPercent / 100);
        $isLovedByEveryone = false;
        if(round($avgRating, 1) >= 4.7){
            $isLovedByEveryone = true;
        }
        $price = $this->calcPrice($checkInDate, $checkOutDate, $maxGuests, $room, $user, false);

        $price_after_discount = $price['final_price'];
                $dayPriceMinHourColumn = match ($dayOfWeek) {
            1 => 'mon_min_hour_price',
            2 => 'tue_min_hour_price',
            3 => 'wed_min_hour_price',
            4 => 'thu_min_hour_price',
            5 => 'fri_min_hour_price',
            6 => 'sat_min_hour_price',
            0 => 'sun_min_hour_price',
        };
        $dayPriceHourBufferColumn = match ($dayOfWeek) {
            1 => 'mon_buffer_price',
            2 => 'tue_buffer_price',
            3 => 'wed_buffer_price',
            4 => 'thu_buffer_price',
            5 => 'fri_buffer_price',
            6 => 'sat_buffer_price',
            0 => 'sun_buffer_price',
        };
        $price_hour = $room->hourlyPricing->first()?->{$dayPriceMinHourColumn} ?? 0;
        $buffer_price_hour = $room->hourlyPricing->first()?->{$dayPriceHourBufferColumn} ?? 0;
        if (!$user) {
            $is100kLandauActive = $this->is100kLandauActive();
            $price_after_discount = $is100kLandauActive
                ? $price['final_price'] - Voucher::MAX_DISCOUNT_AMOUNT
                : $price['final_price'];
            $price_hour = $is100kLandauActive
                ? $price_hour - Voucher::MAX_DISCOUNT_AMOUNT + $buffer_price_hour
                : $price_hour;
        } else {
            $maxDiscount = $room->applied_voucher_discount ?? 0;
            $price_after_discount = $price['final_price'] - $maxDiscount;
            $price_hour = $price_hour - $maxDiscount + $buffer_price_hour;
        }

        $response = [
            'object' => $room->getResourceKey(),
            'id' => $room->id,
            'name' => $room->name,
            'title' => $room->title,
            'description' => $room->description,
            'is_favorite' => $isFavorite,
            'is_loved_by_everyone' => $isLovedByEveryone,
            'code' => $room->code,
            'latitude' => $room->latitude,
            'longitude' => $room->longitude,
            'price' => $price['original_price'],
            'price_min_hour' => $price_hour,
            'address' => $room->address,
            'max_guests' => $room->max_guests,
            'num_beds' => $room->num_beds,
            'num_bathrooms' => $room->num_bathrooms,
            'area_sqm' => $room->area_sqm,
            'avg_rating' => round($avgRating, 1),  // làm tròn 1 chữ số thập phân
            'review_count' => $countReviews,
        ];

        if (empty(request()->is_by_hours)) {
            $response['price_after_discount'] = $price_after_discount;
            $response['last_minute_discount_percent'] = $discountPercent;
        }

        return $response;

    }

    public function includeRoomType(Room $room)
    {
        if ($room->roomType) {
            return $this->primitive([
                'object' => $room->roomType->getResourceKey(),
                'id' => $room->roomType->id,
                'name' => $room->roomType->name,
                'description' => $room->roomType->description,
            ]);
        }
        return $this->primitive(null);
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



    public function includeComboPricing(Room $room)
    {
        if( $room->comboPricing && $room->comboPricing->isNotEmpty() ) {
            $user = auth()->user();
            $checkInTimeStr = request()->check_in_time;
            $checkInTime = $checkInTimeStr ? Carbon::parse($checkInTimeStr) : Carbon::now();
            $maxGuests = request()->max_guests ?? 1;

            $is100kLandauActive = false;
            if (!$user) {
                $is100kLandauActive = $this->is100kLandauActive();
            }

            $combosArray = $room->comboPricing->map(function ($combo) use ($checkInTime, $maxGuests, $room, $user, $is100kLandauActive) {
                $checkInDate = $checkInTime->copy()->setTimeFromTimeString($combo->start_time);
                $checkOutDate = $checkInTime->copy()->setTimeFromTimeString($combo->end_time);
                if ($combo->end_time <= $combo->start_time) {
                    $checkOutDate->addDay();
                }

                $price = $this->calcPrice($checkInDate, $checkOutDate, $maxGuests, $room, $user, false);
                $price_after_discount = $price['final_price'];

                if (!$user) {
                    $price_after_voucher = $is100kLandauActive
                        ? $price['final_price'] - Voucher::MAX_DISCOUNT_AMOUNT
                        : $price['final_price'];
                } else {
                    $maxDiscount = $room->applied_voucher_discount ?? 0;
                    $price_after_voucher = $price['final_price'] - $maxDiscount;
                }

                return [
                    'type' => $combo->getResourceKey(),
                    'id' => $combo->id,
                    'start_time' => $combo->start_time,
                    'end_time' => $combo->end_time,
                    'price' => max(0, $price_after_voucher),
                ];
            })->toArray();
            return $this->primitive($combosArray);
        }
        return $this->primitive([]);
    }

    private function is100kLandauActive(): bool
    {
        static $isActive = null;
        if ($isActive === null) {
            $isActive = Voucher::where('code', Voucher::CODE_100K_LANDAU)
                ->where('is_active', true)
                ->exists();
        }
        return $isActive;
    }
}

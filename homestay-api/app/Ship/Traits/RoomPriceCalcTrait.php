<?php

namespace App\Ship\Traits;

use App\Containers\SharedSection\Order\Actions\ListVoucherAvailableAction;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Containers\SharedSection\Order\UI\API\Transformers\ListVoucherTransformer;
use App\Containers\SharedSection\Room\Models\FixedCheckTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait RoomPriceCalcTrait
{
    function getPriceByDayOfWeek($combo, Carbon $date)
    {
        $map = [
            0 => 'sun_price',
            1 => 'mon_price',
            2 => 'tue_price',
            3 => 'wed_price',
            4 => 'thu_price',
            5 => 'fri_price',
            6 => 'sat_price',
        ];

        $dayOfWeek = $date->dayOfWeek;
        $field = $map[$dayOfWeek];

        return $combo->{$field} ?? 0;
    }

    //Lấy giá theo khung giờ cố định và ngày trong tuần (1 khung giờ)
    public function getFixedCheckTimePrice(Carbon $checkIn, $room, string $code)
    {
        $fixedCheckTime = $room->fixedCheckTime->firstWhere('code', $code);
        if (!$fixedCheckTime) {
            return null;
        }

        $map = [
            0 => 'sun_price',
            1 => 'mon_price',
            2 => 'tue_price',
            3 => 'wed_price',
            4 => 'thu_price',
            5 => 'fri_price',
            6 => 'sat_price',
        ];

        $field = $map[$checkIn->dayOfWeek];
        return $fixedCheckTime->pivot->$field ?? null;
    }

    public function getFixedCheckTimeBuffer(Carbon $checkIn, $room, string $code, string $fieldBuffer)
    {
        $fixedCheckTime = $room->fixedCheckTime->firstWhere('code', $code);
        return $fixedCheckTime->pivot->$fieldBuffer ?? 0;
    }

    //Lấy tổng giá theo khung giờ cố định và ngày trong tuần (nhiều ngày)
    public function getFixedCheckTimeTotalPrice(Carbon $checkIn, Carbon $checkOut, $room, string $code, bool $withBreakdown = false)
    {
        $fixed = $room->fixedCheckTime->firstWhere('code', $code);
        if (!$fixed) {
            return null;
        }

        $map = [
            0 => 'sun_price',
            1 => 'mon_price',
            2 => 'tue_price',
            3 => 'wed_price',
            4 => 'thu_price',
            5 => 'fri_price',
            6 => 'sat_price',
        ];

        $date = $checkIn->copy()->startOfDay();
        $end  = $checkOut->copy()->startOfDay();
        if ($date->gte($end)) {
            return $withBreakdown ? ['total' => 0, 'days' => []] : 0;
        }

        $total = 0;
        $days  = [];

        while ($date->lt($end)) {
            $field = $map[$date->dayOfWeek] ?? null;
            $price = $field ? ($fixed->pivot->$field ?? 0) : 0;

            $total += (float) $price;

            $days[] = [
                'date'  => $date->toDateString(),
                'dow'   => $date->dayOfWeek,
                'price' => (float) $price,
            ];

            $date->addDay();
        }

        return $withBreakdown ? ['total' => $total, 'days' => $days] : $total;
    }

    //Lấy giá theo số giờ phát sinh
    public function getExtraHourPrice(int $hours, $room)
    {
        $pricePerHour = $room->pricingPolicy->extra_hour_price ?? 0;
        return $hours * $pricePerHour;
    }

    //Lấy giá theo số giờ
    public function getHourPrice($checkIn, $checkOut, $room)
    {
        $hourlyPricing = $room->hourlyPricing->first();
        $dayOfWeek = $checkIn->dayOfWeek;
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
        if ($checkIn->diffInHours($checkOut) <= 2) {
            return $hourlyPricing->{$dayPriceMinHourColumn} ?? 0;
        }

        $pricePerHour = $room->pricingPolicy->extra_hour_price ?? 0;
        return ($hourlyPricing->{$dayPriceMinHourColumn} ?? 0) + ($checkIn->diffInHours($checkOut) - 2) * $pricePerHour;
    }

    //Lấy số đêm
    public function getNumberOfNights(Carbon $checkIn, Carbon $checkOut): int
    {
        if ($checkOut->lessThanOrEqualTo($checkIn)) {
            return 0;
        }

        $nights = $checkIn->copy()->startOfDay()->diffInDays($checkOut->copy()->startOfDay());

        if ($nights === 0 && !$checkIn->isSameDay($checkOut)) {
            $nights = 1;
        }

        return $nights;
    }

    //Lấy giá phát sinh theo số khách
    public function getExtraGuestPrice(int $adults, $room): int
    {
        $extraPricePerGuest = $room->pricingPolicy->extra_adult_price ?? 0;
        $baseGuest = $room->base_guests ?? 0;
        if ($adults <= $baseGuest) {
            return 0;
        }

        $extraGuests = $adults - $baseGuest;

        return $extraGuests * $extraPricePerGuest;
    }

    public function isWithinFixedCheckTime($checkIn, $checkOut){
        $sixAm = (clone $checkIn)->setHour(6)->setMinute(0)->setSecond(0);
        $elevenPm = (clone $checkIn)->setHour(23)->setMinute(0)->setSecond(0);
        if($checkIn <= $sixAm){
            return true;
        }
        if($checkIn->diffInHours($checkOut) > 6){
            return true;
        }else if($checkIn->diffInHours($checkOut) == 6){
            if($checkIn > $sixAm && $checkOut <= $elevenPm){
                return true;
            }
        }
        
        return false;
    }
    public function calcPrice(Carbon $checkIn,
                              Carbon $checkOut,
                              int $adults,
                                     $room,
                                     $user = null,
                              bool $withVoucher = true,
                              ?bool $hasVoucher = null, $order = null)
    {



        //Kiểm tra xem có voucher nào avaiable không
        $user ??= Auth::user();
        $hasVoucher ??= $this->getMaxGlobalVoucherDiscount($user) > 0;
        $voucher100k = false;
        if($this->getMaxGlobalVoucherDiscount($user) == 100000){
            $voucher100k = true;
        }

        $mapBuffer = [
            0 => 'sun_buffer_price',
            1 => 'mon_buffer_price',
            2 => 'tue_buffer_price',
            3 => 'wed_buffer_price',
            4 => 'thu_buffer_price',
            5 => 'fri_buffer_price',
            6 => 'sat_buffer_price',
        ];
        $fieldBuffer = $mapBuffer[$checkIn->dayOfWeek];
        
        $fixedBuffer = $room->fixedCheckTime->first()->pivot->$fieldBuffer ?? 0;
        $hourlyBuffer = $room->hourlyPricing->first()->{$fieldBuffer} ?? 0;

        $buffer = $fixedBuffer;

        $price = 0;


        $sixAm = (clone $checkIn)->setHour(6)->setMinute(0)->setSecond(0);
        $elevenAm1 = (clone $checkIn)->setHour(11)->setMinute(0)->setSecond(0);
        $elevenAm2 = (clone $checkOut)->setHour(11)->setMinute(0)->setSecond(0);
        $nineAm1 = (clone $checkIn)->setHour(9)->setMinute(0)->setSecond(0);
        $nineAm2 = (clone $checkOut)->setHour(9)->setMinute(0)->setSecond(0);
        $tenAm1 = (clone $checkIn)->setHour(10)->setMinute(0)->setSecond(0);
        $twoPm = (clone $checkIn)->setHour(14)->setMinute(0)->setSecond(0);
        $tenPm = (clone $checkIn)->setHour(22)->setMinute(0)->setSecond(0);
        $ninePm = (clone $checkIn)->setHour(21)->setMinute(0)->setSecond(0);
        $eightPm = (clone $checkIn)->setHour(20)->setMinute(0)->setSecond(0);
        $ninePm2 = (clone $checkOut)->setHour(21)->setMinute(0)->setSecond(0);
        $tenPm2 = (clone $checkOut)->setHour(22)->setMinute(0)->setSecond(0);
        $elevenPm= (clone $checkIn)->setHour(23)->setMinute(0)->setSecond(0);
        //Cùng 1 ngày checkin checkout
        if ($checkIn->isSameDay($checkOut)) {
            foreach ($room->comboPricing as $combo) {
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $checkIn->format('Y-m-d') . ' ' . str_pad($combo->start_time, 2, '0', STR_PAD_LEFT) . ':00:00');
                $end = Carbon::createFromFormat('Y-m-d H:i:s', $checkIn->format('Y-m-d') . ' ' . str_pad($combo->end_time, 2, '0', STR_PAD_LEFT) . ':00:00');
                if ($checkIn->eq($start) && $checkOut->eq($end)) {
                    $buffer = $hasVoucher ? ($combo->$fieldBuffer ?? 0) : 0;
                    $price = $price + $this->getPriceByDayOfWeek($combo, $checkIn);
                    $price = $price + $buffer;
                    $comboData = [
                        'original_price' => $price,
                        'discount_amount' => 0,
                        'final_price' => $price,
                        'buffer' => $buffer
                    ];
                    return $withVoucher
                        ? $this->appendVoucherInfo($comboData, $user)
                        : $comboData + [
                            'price_after_voucher' => $price,
                            'max_voucher_discount_amount' => 0,
                            'applied_voucher_id' => null,
                            'applied_voucher_code' => null,
                        ];
                }
            }
            if (($checkIn->diffInHours($checkOut) >= 6  && $checkIn > $sixAm && $checkOut <= $elevenPm)) {
                if(($checkIn->eq($tenAm1) && $checkOut->eq($eightPm))  || $checkIn >= $tenAm1 && $checkOut <= $eightPm){
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'day_time');
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'day_time', $fieldBuffer);
                }elseif ($checkIn < $tenAm1 && $checkOut <= $eightPm) {
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'day_time') + ($checkIn->diffInHours($tenAm1) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($checkIn->diffInHours($tenAm1), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'day_time', $fieldBuffer);
                }elseif ( $checkIn < $tenAm1 && $checkOut > $eightPm) {
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'day_time') +  (
                    (( $checkIn->diffInHours($tenAm1) + $eightPm->diffInHours($checkOut) ) >= 7 )
                    ? $this->getFixedCheckTimePrice($checkIn, $room, 'std')
                    : ($this->getExtraHourPrice($checkIn->diffInHours($tenAm1), $room) + $this->getExtraHourPrice($eightPm->diffInHours($checkOut), $room)));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'day_time', $fieldBuffer);
                }else{
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'day_time') + ($eightPm->diffInHours($checkOut) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($eightPm->diffInHours($checkOut), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'day_time', $fieldBuffer);
                }
            }
            else if ($checkOut >= $tenPm) {
                if($checkIn->diffInHours($checkOut) <= 6){
                    $buffer = $hourlyBuffer;
                    $price = $price + $this->getHourPrice($checkIn, $checkOut, $room);
                }else{
                    if ($checkIn >= $tenPm) {
                        $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'overnight');
                        $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'overnight', $fieldBuffer);
                    } elseif ($checkIn >= $twoPm) {
                        $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std');
                        $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                    } elseif ($checkIn > $sixAm) {
                        $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std') + ($checkIn->diffInHours($twoPm) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($checkIn->diffInHours($twoPm), $room));
                        $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                    } else {
                        $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std') + $this->getFixedCheckTimePrice($checkIn->copy()->subDay(), $room, 'std');
                        $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                    }
                }
            } else {
                if ($checkIn <= $sixAm) {
                    if ($checkOut > $elevenAm1) {
                        $price = $price + $this->getFixedCheckTimePrice($checkIn->copy()->subDay(), $room, 'std') + ($elevenAm1->diffInHours($checkOut) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($elevenAm1->diffInHours($checkOut), $room));
                        $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                    }elseif($checkOut > $nineAm1){
                         $price = $price + $this->getFixedCheckTimePrice($checkIn->copy()->subDay(), $room, 'std');
                         $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                    }else{
                        $price = $price + $this->getFixedCheckTimePrice($checkIn->copy()->subDay(), $room, 'overnight');
                        $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'overnight', $fieldBuffer);
                    }
                }else{
                    $buffer = $hourlyBuffer;
                    $price = $price + $this->getHourPrice($checkIn, $checkOut, $room);
                }
            }
        }
        //Checkin và checkout cách nhau 1 ngày
        elseif ($this->getNumberOfNights($checkIn, $checkOut) == 1) {
            //Checkin sau 9PM
            if ($checkIn >= $ninePm) {
                if($checkOut <= $nineAm2){
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'overnight');
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'overnight', $fieldBuffer);
                }elseif ($checkOut <= $elevenAm2) {
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std');
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                } else {
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std') +  ($elevenAm2->diffInHours($checkOut) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($elevenAm2->diffInHours($checkOut), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }
            } elseif ($checkIn >= $twoPm) { //checkin sau 2PM
                if ($checkOut <= $elevenAm2) {
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std');
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }elseif ($checkOut < $tenPm2){
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std') +  ($elevenAm2->diffInHours($checkOut) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($elevenAm2->diffInHours($checkOut), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }else {
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std')*2;
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }
            } elseif($checkIn <= $sixAm) {  //checkin trước 6AM
                if ($checkOut <= $elevenAm2) {
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std') * 2;
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                } elseif ($checkOut > $elevenAm2 && $checkOut < $tenPm2) {
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std') * 2 + ($elevenAm2->diffInHours($checkOut) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($elevenAm2->diffInHours($checkOut), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }else {
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std') * 3;
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }
            }
            else{  //checkin từ 6AM đến trước 2PM
                if($checkOut <= $elevenAm2){
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std') + ($checkIn->diffInHours($twoPm) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($checkIn->diffInHours($twoPm), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }elseif($checkOut >= $tenPm2){
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std') + ($checkIn->diffInHours($twoPm) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($checkIn->diffInHours($twoPm), $room)) + $this->getFixedCheckTimePrice($checkIn, $room, 'std');
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }

                else {
                    $price = $price + $this->getFixedCheckTimePrice($checkIn, $room, 'std') + ($checkIn->diffInHours($twoPm) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($checkIn->diffInHours($twoPm), $room)) + ($elevenAm2->diffInHours($checkOut) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($elevenAm2->diffInHours($checkOut), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }
            }
        }
        //Checkin và checkout cách nhau nhiều ngày
        else {
            if ($checkIn >= $twoPm) {
                if ($checkOut <= $elevenAm2) {
                    $price = $price + $this->getFixedCheckTimeTotalPrice($checkIn,$checkOut, $room, 'std');
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                } elseif($checkOut >= $tenPm2){
                    $price =$price + $this->getFixedCheckTimeTotalPrice($checkIn,$checkOut, $room, 'std') + $this->getFixedCheckTimePrice($checkOut, $room, 'std');
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }
                else {
                    $price = $price + $this->getFixedCheckTimeTotalPrice($checkIn,$checkOut, $room, 'std') + ($elevenAm2->diffInHours($checkOut) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($elevenAm2->diffInHours($checkOut), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }
            } elseif ($checkIn > $sixAm) {
                if ($checkOut <= $elevenAm2) {
                    $price = $price + $this->getFixedCheckTimeTotalPrice($checkIn,$checkOut, $room, 'std') + ($checkIn->diffInHours($twoPm) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($checkIn->diffInHours($twoPm), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }elseif($checkOut >= $tenPm2){
                    $price = $price + $this->getFixedCheckTimeTotalPrice($checkIn,$checkOut, $room, 'std') + ($checkIn->diffInHours($twoPm) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($checkIn->diffInHours($twoPm), $room)) + $this->getFixedCheckTimePrice($checkOut, $room, 'std');
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }
                else {
                    $price = $price + $this->getFixedCheckTimeTotalPrice($checkIn,$checkOut, $room, 'std') + ($checkIn->diffInHours($twoPm) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($checkIn->diffInHours($twoPm), $room)) + ($elevenAm2->diffInHours($checkOut) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($elevenAm2->diffInHours($checkOut), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }
            } else {
                if ($checkOut <= $elevenAm2) {
                    $price = $price + $this->getFixedCheckTimeTotalPrice($checkIn,$checkOut, $room, 'std') + $this->getFixedCheckTimePrice($checkIn->copy()->subDay(), $room, 'std');
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                } elseif($checkOut >= $tenPm2) {
                    $price = $price + $this->getFixedCheckTimeTotalPrice($checkIn,$checkOut, $room, 'std') + $this->getFixedCheckTimePrice($checkIn->copy()->subDay(), $room, 'std') + $this->getFixedCheckTimePrice($checkOut, $room, 'std');
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }else{
                    $price = $price + $this->getFixedCheckTimeTotalPrice($checkIn,$checkOut, $room, 'std') + $this->getFixedCheckTimePrice($checkIn->copy()->subDay(), $room, 'std' ) +  ($elevenAm2->diffInHours($checkOut) >= 7 ? $this->getFixedCheckTimePrice($checkIn, $room, 'std') : $this->getExtraHourPrice($elevenAm2->diffInHours($checkOut), $room));
                    $buffer = $this->getFixedCheckTimeBuffer($checkIn, $room, 'std', $fieldBuffer);
                }
            }
        }

        if (!$hasVoucher) {
            $buffer = 0;
        }

        $discountSpecialOffer = $room->specialOffers?->first();
        $now = Carbon::now();
        $originPrice = $price;
        $price = $price * (1 - ($room->system_discount_percent ?? 0) / 100);
        $discountAmount = $originPrice - $price;
        if ($discountSpecialOffer && $checkIn->lte($now->copy()->addHours($discountSpecialOffer->last_minute_hours))) {
            if(($checkIn < $ninePm || $checkOut > $nineAm2) && $this->getNumberOfNights($checkIn, $checkOut) >= 1){
                $discountPercent = $discountSpecialOffer->last_minute_discount_percent ?? 0;
                $discountAmount = $this->getFixedCheckTimePrice($checkIn, $room, 'std') *  (   $discountPercent / 100 + ($room->system_discount_percent ?? 0) / 100);
                $price = $originPrice - $discountAmount;
            }
        }
        //Mặc định giá theo số khách
        $price = $price + $this->getExtraGuestPrice($adults, $room);
        $originPrice = $originPrice + $this->getExtraGuestPrice($adults, $room);
        if(!empty($order)){
            if($order->voucher_id == Voucher::ID_100K){
                $buffer = $buffer + 10000;
            }
        }
        //Nếu voucher 100k Avaiable thì cộng thêm 10k và buffer
        if($voucher100k){
            $buffer = $buffer + 10000;
        }
        $price = $price + $buffer;
        $originPrice = $originPrice + $buffer;
        $priceData = [
            'original_price'=>$originPrice,
            'discount_amount' => (int)$discountAmount,
            'final_price' =>$price,
            'buffer' => (float)$buffer
        ];
        return $withVoucher
            ? $this->appendVoucherInfo($priceData, $user)
            : $priceData + [
                'price_after_voucher' => $price,
                'max_voucher_discount_amount' => 0,
                'applied_voucher_id' => null,
                'applied_voucher_code' => null,
            ];
    }

    private function appendVoucherInfo(array $priceData, $user = null)
    {
        $finalPrice = $priceData['final_price'];
        
        $maxDiscount = 0;
        $bestVoucherId = null;
        $bestVoucherCode = null;

        if (!$user) {
            $is100kLandauActive = Voucher::where('code', Voucher::CODE_100K_LANDAU)
                ->where('is_active', true)
                ->exists();
            $maxDiscount = $is100kLandauActive ? Voucher::MAX_DISCOUNT_AMOUNT : 0;
        } else {
            $oldPriceTotal = request()->input('price_total');
            // Cần truyền original_price vào request để tính toán giá trị voucher
            request()->merge(['price_total' => $priceData['original_price']]);

            $action = app(ListVoucherAvailableAction::class);
            $vouchers = $action->run(request(), $user->id);

            $transformer = new ListVoucherTransformer();
            
            foreach ($vouchers as $voucher) {
                $transformedData = $transformer->transform($voucher);
                if (($transformedData['available'] ?? false) && isset($transformedData['discount_amount'])) {
                    if ($transformedData['discount_amount'] > $maxDiscount) {
                        $maxDiscount = $transformedData['discount_amount'];
                        $bestVoucherId = $transformedData['id'] ?? null;
                        $bestVoucherCode = $transformedData['code'] ?? null;
                    }
                }
            }

            // Phục hồi lại request gốc
            if (!is_null($oldPriceTotal)) {
                request()->merge(['price_total' => $oldPriceTotal]);
            } else {
                request()->request->remove('price_total');
                request()->query->remove('price_total');
            }
        }

        // Bổ sung thêm response sau khi áp dụng voucher lớn nhất
        $priceData['price_after_voucher'] = $finalPrice - $maxDiscount;
        $priceData['final_price'] = $finalPrice;
        $priceData['max_voucher_discount_amount'] = (int) $maxDiscount;
        $priceData['applied_voucher_id'] = $bestVoucherId;
        $priceData['applied_voucher_code'] = $bestVoucherCode;

        return $priceData;
    }

    public function getMaxGlobalVoucherDiscount($user = null)
    {
        static $cachedDiscounts = [];

        if (!$user) {
            $is100kLandauActive = Voucher::where('code', Voucher::CODE_100K_LANDAU)
                ->where('is_active', true)
                ->exists();
            return $is100kLandauActive ? Voucher::MAX_DISCOUNT_AMOUNT : 0;
        }

        if (array_key_exists($user->id, $cachedDiscounts)) {
            return $cachedDiscounts[$user->id];
        }

        $maxDiscount = 0;

        $oldPriceTotal = request()->input('price_total');
        request()->merge(['price_total' => 999999999]);

            $action = app(ListVoucherAvailableAction::class);
            $vouchers = $action->run(request(), $user->id)->filter(fn($v) => $v->discount_type === 'fixed');
            $transformer = new ListVoucherTransformer();
            foreach ($vouchers as $voucher) {
                $transformedData = $transformer->transform($voucher);
                if (($transformedData['available'] ?? false) && isset($transformedData['discount_amount'])) {
                    if ($transformedData['discount_amount'] > $maxDiscount) {
                        $maxDiscount = $transformedData['discount_amount'];
                    }
                }
            }

            if (!is_null($oldPriceTotal)) {
                request()->merge(['price_total' => $oldPriceTotal]);
            } else {
                request()->request->remove('price_total');
                request()->query->remove('price_total');
            }
        $cachedDiscounts[$user->id] = $maxDiscount;
        return $maxDiscount;
    }
}

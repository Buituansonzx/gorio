<?php

namespace App\Containers\AdminSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateRoomTimePricingRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        $priceFields = [
            'price', 'currency',
            'mon_price', 'tue_price', 'wed_price', 'thu_price', 'fri_price', 'sat_price', 'sun_price',
            'mon_buffer_price', 'tue_buffer_price', 'wed_buffer_price', 'thu_buffer_price',
            'fri_buffer_price', 'sat_buffer_price', 'sun_buffer_price',
        ];
        $hourlyPriceFields = [
            'min_hours_price',
            'mon_min_hour_price', 'tue_min_hour_price', 'wed_min_hour_price', 'thu_min_hour_price',
            'fri_min_hour_price', 'sat_min_hour_price', 'sun_min_hour_price',
            'mon_buffer_price', 'tue_buffer_price', 'wed_buffer_price', 'thu_buffer_price',
            'fri_buffer_price', 'sat_buffer_price', 'sun_buffer_price',
        ];

        $rules = [
            'fixed_check_times' => 'sometimes|array',
            'fixed_check_times.*.id' => 'required_with:fixed_check_times|string|exists:fixed_check_times,id',

            'combo_pricing' => 'sometimes|array',
            'combo_pricing.*.id' => 'required_with:combo_pricing|string|exists:room_combo_pricing,id',
            'combo_pricing.*.start_time' => 'sometimes|nullable|integer',
            'combo_pricing.*.end_time' => 'sometimes|nullable|integer',

            'hourly_pricing' => 'sometimes|array',
            'hourly_pricing.*.id' => 'required_with:hourly_pricing|string|exists:room_hourly_pricing,id',
        ];

        foreach ($priceFields as $field) {
            if ($field === 'currency') {
                $rules["fixed_check_times.*.$field"] = 'sometimes|nullable|string|size:3';
                $rules["combo_pricing.*.$field"] = 'sometimes|nullable|string|size:3';
            } else {
                $rules["fixed_check_times.*.$field"] = 'sometimes|nullable|integer|min:0';
                $rules["combo_pricing.*.$field"] = 'sometimes|nullable|integer|min:0';
            }
        }

        foreach ($hourlyPriceFields as $field) {
            $rules["hourly_pricing.*.$field"] = 'sometimes|nullable|integer|min:0';
        }

        return $rules;
    }
}

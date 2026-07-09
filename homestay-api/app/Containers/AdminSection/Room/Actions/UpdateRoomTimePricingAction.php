<?php

namespace App\Containers\AdminSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomComboPricing;
use App\Containers\SharedSection\Room\Models\RoomHourlyPricing;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;

final class UpdateRoomTimePricingAction extends ParentAction
{
    private const PIVOT_PRICE_FIELDS = [
        'price',
        'mon_price', 'tue_price', 'wed_price', 'thu_price', 'fri_price', 'sat_price', 'sun_price',
        'mon_buffer_price', 'tue_buffer_price', 'wed_buffer_price', 'thu_buffer_price',
        'fri_buffer_price', 'sat_buffer_price', 'sun_buffer_price',
    ];

    private const COMBO_FIELDS = [
        'start_time', 'end_time', 'price', 'currency',
        'mon_price', 'tue_price', 'wed_price', 'thu_price', 'fri_price', 'sat_price', 'sun_price',
        'mon_buffer_price', 'tue_buffer_price', 'wed_buffer_price', 'thu_buffer_price',
        'fri_buffer_price', 'sat_buffer_price', 'sun_buffer_price',
    ];

    private const HOURLY_FIELDS = [
        'min_hours_price',
        'mon_min_hour_price', 'tue_min_hour_price', 'wed_min_hour_price', 'thu_min_hour_price',
        'fri_min_hour_price', 'sat_min_hour_price', 'sun_min_hour_price',
        'mon_buffer_price', 'tue_buffer_price', 'wed_buffer_price', 'thu_buffer_price',
        'fri_buffer_price', 'sat_buffer_price', 'sun_buffer_price',
    ];

    public function run(string $roomId, array $data): array
    {
        $room = Room::findOrFail($roomId);

        DB::transaction(function () use ($room, $data) {
            foreach ($data['fixed_check_times'] ?? [] as $item) {
                $pivotData = array_intersect_key($item, array_flip(self::PIVOT_PRICE_FIELDS));
                if (empty($pivotData)) {
                    continue;
                }
                $room->fixedCheckTime()->updateExistingPivot($item['id'], $pivotData);
            }

            foreach ($data['combo_pricing'] ?? [] as $item) {
                $updateData = array_intersect_key($item, array_flip(self::COMBO_FIELDS));
                if (empty($updateData)) {
                    continue;
                }
                RoomComboPricing::where('id', $item['id'])
                    ->where('room_id', $room->id)
                    ->update($updateData);
            }

            foreach ($data['hourly_pricing'] ?? [] as $item) {
                $updateData = array_intersect_key($item, array_flip(self::HOURLY_FIELDS));
                if (empty($updateData)) {
                    continue;
                }
                RoomHourlyPricing::where('id', $item['id'])
                    ->where('room_id', $room->id)
                    ->update($updateData);
            }
        });

        $room->load(['fixedCheckTime', 'comboPricing', 'hourlyPricing']);

        return [
            'fixed_check_times' => $room->fixedCheckTime,
            'combo_pricing' => $room->comboPricing,
            'hourly_pricing' => $room->hourlyPricing,
        ];
    }
}

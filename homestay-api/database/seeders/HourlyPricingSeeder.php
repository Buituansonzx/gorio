<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HourlyPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::with('hourlyPricing')->get();
        foreach ($rooms as $room) {
            $hourlyPrice = $room->hourlyPricing->first();
            if($hourlyPrice){
                $hourlyPrice->update([
                    'mon_min_hour_price' => $hourlyPrice->min_hours_price,
                    'tue_min_hour_price' => $hourlyPrice->min_hours_price,
                    'wed_min_hour_price' => $hourlyPrice->min_hours_price,
                    'thu_min_hour_price' => $hourlyPrice->min_hours_price,
                    'fri_min_hour_price' => $hourlyPrice->min_hours_price,
                    'sat_min_hour_price' => $hourlyPrice->min_hours_price,
                    'sun_min_hour_price' => $hourlyPrice->min_hours_price,
                    'mon_buffer_price' => $hourlyPrice->buffer_price,
                    'tue_buffer_price' => $hourlyPrice->buffer_price,
                    'wed_buffer_price' => $hourlyPrice->buffer_price,
                    'thu_buffer_price' => $hourlyPrice->buffer_price,
                    'fri_buffer_price' => $hourlyPrice->buffer_price,
                    'sat_buffer_price' => $hourlyPrice->buffer_price,
                    'sun_buffer_price' => $hourlyPrice->buffer_price,
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HourPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::whereDoesntHave('hourlyPricing')->get();
        foreach ($rooms as $room) {
            $room->hourlyPricing()->create(
                [
                    'min_hours' => 2,
                    'min_hours_price' => 250000,
                    'next_hour_price' => 90000,
                    'is_active' => true,
                ],
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\HouseRule;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomHouseRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DishwashingFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();
        $houseRuleDishwashingFee= HouseRule::where('code', 'dishwashing_fee')->first();

        foreach ($rooms as $room) {
            if(RoomHouseRule::where('room_id', $room->id)->where('house_rule_id', $houseRuleDishwashingFee->id)->exists()){
                continue;
            }
            RoomHouseRule::create([
                'room_id' => $room->id,
                'house_rule_id' => $houseRuleDishwashingFee->id,
                'value' => '50000',
            ]);
        }
    }
}

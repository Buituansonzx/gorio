<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoomSpecialOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::whereDoesntHave('specialOffers')->chunkById(500, function ($rooms) {
            $inserts = [];
            $now = now();

            foreach ($rooms as $room) {
                $inserts[] = [
                    'id' => Str::uuid()->toString(),
                    'room_id' => $room->id,
                    'last_minute_hours' => 2,
                    'last_minute_discount_percent' => 10,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($inserts)) {
                DB::table('room_special_offers')->insert($inserts);
            }
        });
    }
}

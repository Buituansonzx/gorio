<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\Amenity;
use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomDuplexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::whereIn('name', [
            '303 Trần Phú',
            '403 Trần Phú',
            '304 Trần Phú',
            '404 Trần Phú',
            '503 Trần Phú',
            '603 Trần Phú',
            '604 Trần Phú',
        ])->get();

        $duplexAmenity = Amenity::where('code', 'duplex')->first();
        foreach ( $rooms as $room) {
            $room->amenities()->syncWithoutDetaching([
                $duplexAmenity->id
            ]);
        }

    }
}

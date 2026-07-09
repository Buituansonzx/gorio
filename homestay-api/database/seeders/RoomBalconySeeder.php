<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\Amenity;
use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomBalconySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::whereIn('name', [
            '402 Định Công Thượng',
            '404 Định Công Thượng',
            '502 Định Công Thượng',
            '504 Định Công Thượng',
            '602 Định Công Thượng',
            '604 Định Công Thượng',
            '301 Trung Kính',
            '401 Trung Kính',
            '501 Trung Kính',
            '201 Đội Cấn 1',
            '301 Đội Cấn 1',
            '401 Đội Cấn 1',
            '501 Đội Cấn 1',
            '502 Thái Hà',
            '602 Thái Hà',
            '802 Thái Hà',
            '701 Thái Hà',
            '601 Thái Hà',
            '601 Tôn Đức Thắng',
            '401 Đội Cấn 2',
            '501 Đội Cấn 2',
            '601 Đội Cấn 2',
            '701 Đội Cấn 2',
            '801 Đội Cấn 2',
            '201 Âu Cơ',
            '202 Âu Cơ',
            '301 Âu Cơ',
            '302 Âu Cơ',
            '401 Âu Cơ',
            '501 Âu Cơ',
            '201 Võng Thị',
            '301 Võng Thị',
            '401 Võng Thị',
            '501 Võng Thị',
            '601 Võng Thị',
            '301 Lạc Long Quân 2',
            '401 Lạc Long Quân 2',
            '601 Lạc Long Quân 2',
            '301 Vĩnh Hồ',
            '401 Vĩnh Hồ',
            '501 Vĩnh Hồ',
            '601 Vĩnh Hồ',
            '701 Vĩnh Hồ',
            '201 Mễ Trì',
            '301 Mễ Trì',
            '401 Mễ Trì',
            '501 Mễ Trì',
            '601 Mễ Trì',
            '701 Mễ Trì',
            '201 Kim Ngưu',
            '203 Kim Ngưu',
            '301 Kim Ngưu',
            '303 Kim Ngưu',
            '401 Kim Ngưu',
            '403 Kim Ngưu',
        ])->get();
        $balconyAmenity = Amenity::where('code', 'patio_or_balcony')->first();
        foreach ($rooms as $room) {
            $room->amenities()->syncWithoutDetaching([
                $balconyAmenity->id
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolicyAllRoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = 'cancellation';
        $content = 'Hủy trước giờ nhận phòng ít nhất 48h: hoàn tiền đầy đủ, nhận lại 100% số tiền bạn đã thanh toán.
Hủy trước giờ nhận phòng ít nhất 24h: hoàn tiền một phần,  nhận lại 50% số tiền bạn đã thanh toán.
Hủy trước giờ nhận phòng dưới 24h: không hoàn tiền';
        $rooms = Room::all();
        foreach ($rooms as $room) {
            $room->roomPolicy()->updateOrCreate(
                ['policy_type' => $type],
                ['content' => $content]
            );
        }
    }
}

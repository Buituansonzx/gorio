<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\CheckoutInstructionType;
use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomCheckoutInstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $checkoutInstructionCodes = [
            'collect_used_towels',
            'custom_request',
            'lock_doors',
            'return_keys',
            'take_out_trash',
            'turn_off_devices',
        ];

        // 1. Load toàn bộ instruction types một lần
        $instructionTypes = CheckoutInstructionType::whereIn('code', $checkoutInstructionCodes)
            ->get()
            ->keyBy('code');

        if ($instructionTypes->isEmpty()) {
            return;
        }

        // 2. TỐI ƯU: Khởi tạo mảng data sync MỘT LẦN DUY NHẤT ở ngoài vòng lặp
        $syncData = [];
        foreach ($checkoutInstructionCodes as $code) {
            if (!isset($instructionTypes[$code])) {
                continue;
            }

            if ($code === 'custom_request') {
                $syncData[$instructionTypes[$code]->id] = [
                    'content' => 'Dọn dẹp bát đũa sau khi sử dụng',
                ];
            } else {
                $syncData[$instructionTypes[$code]->id] = [];
            }
        }

        // Nếu không có dữ liệu để map thì dừng luôn
        if (empty($syncData)) {
            return;
        }

        // 3. SỬA LỖI: Dùng chunkById() với insert mass để tối ưu tốc độ và không gặp timeout
        Room::whereDoesntHave('checkoutInstructionType')
            ->chunkById(500, function ($rooms) use ($syncData) {
                $inserts = [];
                $now = now();

                foreach ($rooms as $room) {
                    foreach ($syncData as $instructionTypeId => $data) {
                        $inserts[] = [
                            'room_id' => $room->id,
                            'checkout_instruction_type_id' => $instructionTypeId,
                            'content' => $data['content'] ?? null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

                if (!empty($inserts)) {
                    DB::table('room_checkout_instructions_type')->insert($inserts);
                }
            });
    }
}

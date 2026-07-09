<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\HouseRule;
use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateHouseRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::with('houseRule')->get();
        foreach ($rooms as $room) {
            foreach ($room->houseRule as $houseRule) {
                if($houseRule->pivot->house_rule_id == HouseRule::ID_ADD_RULES){
                    $room->houseRule()->updateExistingPivot(
                        $houseRule->id,
                        [
                            'value' => 'Vui lòng cung cấp đầy đủ CCCD trước khi check-in nếu không đơn sẽ bị hủy và không hoàn tiền.
                            Không mang vũ khí, chất độc, chất cấm, chất gây cháy nổ,...
                            Tự chịu trách nhiệm về tài sản của mình. Homestay không chịu trách nhiệm nếu bạn mất.
                            Giữ vệ sinh chung, không mang đồ ăn nặng mùi vào phòng.
                            Vui lòng không tự ý di chuyển đồ đạc trong phòng.
                            Để rác đúng nơi quy định, không bỏ rác vào bồn cầu.
                            Dọn dẹp bếp và rửa bát đũa, dụng cụ sau khi sử dụng.
                            Khóa cổng cẩn thận và để xe đúng nơi quy đinh, không khóa cổ xe.
                            Không tự ý lấy đồ trong phòng và kho dự trữ, không gây hư hại cơ sở vật chất của homestay.'
                        ]
                    );
                }
            }
        }
    }
}

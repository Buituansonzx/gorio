<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\CheckinMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

final class  CheckinMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'code' => 'fully_self_checkin',
                'name' => [
                    'vi' => 'Tự check-in Hoàn toàn',
                    'en' => 'Fully self check-in',
                ],
                'description' => [
                    'vi' => 'Khách tự nhận phòng 100% theo hướng dẫn, không cần tương tác với chủ nhà',
                    'en' => 'Guests check in 100% by instructions, no need to interact with the host',
                ],
            ],
            [
                'code' => 'partially_self_checkin',
                'name' => [
                    'vi' => 'Tự check-in Một phần',
                    'en' => 'Partially self check-in',
                ],
                'description' => [
                    'vi' => 'Khách hàng được cung cấp Hướng dẫn checkin nhưng vẫn cần liên hệ chủ nhà trước để nhận mật khẩu hoặc mở cửa.',
                    'en' => 'Guests are provided check-in instructions but still need to contact the host to get the password or open the door.',
                ],
            ],
            [
                'code' => 'host_meet_checkin',
                'name' => [
                    'vi' => 'Chủ nhà/Quản gia tiếp đón',
                    'en' => 'Host/Manager greets',
                ],
                'description' => [
                    'vi' => 'Khi checkin, khách hàng sẽ được người phụ trách bên bạn hẹn thời gian để đón tiếp và đưa lên phòng',
                    'en' => 'Upon check-in, guests will be greeted and escorted to the room by your staff or host.',
                ],
            ],
            [
                'code' => 'reception_24_7',
                'name' => [
                    'vi' => 'Sảnh lễ tân mở 24/24',
                    'en' => '24/7 Reception',
                ],
                'description' => [
                    'vi' => 'Khách hàng có thể tìm đến sảnh lễ tân để được trợ giúp đưa lên phòng khi checkin.',
                    'en' => 'Guests can go to the reception for assistance in getting to their room upon check-in.',
                ],
            ],
            [
                'code' => 'reception_limited_hours',
                'name' => [
                    'vi' => 'Sảnh lễ tân có quy định thời gian hỗ trợ',
                    'en' => 'Limited hours reception',
                ],
                'description' => [
                    'vi' => 'Khách hàng có thể tìm đến sảnh lễ tân để được trợ giúp đưa lên phòng khi checkin tuy nhiên sẽ có giới hạn thời gian làm việc, ngoài thời gian đó khách hàng sẽ cần liên hệ trước để chủ nhà sắp xếp',
                    'en' => 'Guests can go to the reception for assistance in getting to their room upon check-in, but there are limited working hours. Outside of these hours, guests need to contact the host in advance for arrangements.',
                ],
            ],
        ];

        foreach ($methods as $method) {
            $exists = CheckinMethod::where('code', $method['code'])->exists();

            if (!$exists) {
                CheckinMethod::create([
                    'code' => $method['code'],
                    'name' => $method['name'],
                    'description' => $method['description'],
                ]);
            }
        }
    }
}

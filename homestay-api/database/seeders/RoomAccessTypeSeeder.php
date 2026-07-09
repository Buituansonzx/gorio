<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\RoomAccessType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomAccessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => 'full_private',
                'name' => [
                    'vi' => 'Phòng khép kín toàn bộ',
                    'en' => 'Entire private room',
                ],
                'description' => [
                    'vi' => 'Khách hàng sẽ được toàn quyền sử dụng riêng toàn bộ chỗ ở này mà không phải chia sẻ với các khách hàng khác hoặc chủ nhà, bao gồm cả lối đi lại và các khu vực tiện ích khác mà bạn cung cấp.',
                    'en' => 'Guests have exclusive use of the entire accommodation, without sharing with other guests or the host, including all access ways and amenities you provide.'
                ]
            ],
            [
                'code' => 'private_room',
                'name' => [
                    'vi' => 'Phòng riêng',
                    'en' => 'Private room',
                ],
                'description' => [
                    'vi' => 'Khách hàng được sử dụng phòng riêng biệt với vệ sinh khép kín. Khách hàng có thể sẽ phải chia sẻ với các khách hàng khác hoặc chủ nhà lối đi lại, các tiện ích chung trong khu vực chỗ ở mà bạn cung cấp.',
                    'en' => 'Guests have a private room with an en-suite bathroom. Shared access ways and common amenities with other guests or the host may apply.'
                ]
            ],
            [
                'code' => 'shared_room',
                'name' => [
                    'vi' => 'Phòng chung',
                    'en' => 'Shared room',
                ],
                'description' => [
                    'vi' => 'Khách hàng được sử dụng chỗ ngủ riêng biệt với vệ sinh không khép kín. Khách hàng có thể sẽ phải chia sẻ với các khách hàng khác hoặc chủ nhà lối đi lại, các tiện ích chung trong khu vực chỗ ở mà bạn cung cấp.',
                    'en' => 'Guests have a separate sleeping space without a private bathroom. Shared access ways and amenities with other guests or the host may apply.'
                ]
            ]
        ];

        foreach ($data as $item) {
            $existing = RoomAccessType::where('code', $item['code'])->first();
            if (!$existing) {
                RoomAccessType::create([
                    'code' => $item['code'],
                    'name' => $item['name'],
                    'description' => $item['description'],
                ]);
            }
        }
    }
}

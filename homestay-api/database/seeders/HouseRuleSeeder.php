<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\HouseRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

final class HouseRuleSeeder extends Seeder
{
    public function run(): void
    {
        $houseRules = [
            [
                'code' => 'allow_pets',
                'name' => [
                    'vi' => 'Được phép mang theo thú cưng',
                    'en' => 'Allowed to bring pets',
                ],
                'type' => 'Boolean',
                'is_required' => true,
            ],
            [
                'code' => 'allow_events',
                'name' => [
                    'vi' => 'Được phép tổ chức sự kiện',
                    'en' => 'Allowed to hold events',
                ],
                'type' => 'Boolean',
                'is_required' => true,
            ],
            [
                'code' => 'allow_smoking',
                'name' => [
                    'vi' => 'Cho phép hút thuốc',
                    'en' => 'Smoking allowed',
                ],
                'type' => 'Boolean',
                'is_required' => true,
            ],
            [
                'code' => 'allow_commercial_photography',
                'name' => [
                    'vi' => 'Cho phép chụp ảnh và quay phim vì mục đích thương mại',
                    'en' => 'Commercial photography and filming allowed',
                ],
                'type' => 'Boolean',
                'is_required' => true,
            ],
            [
                'code' => 'quiet_hours',
                'name' => [
                    'vi' => 'Khung giờ giữ im lặng',
                    'en' => 'Quiet hours',
                ],
                'type' => 'string (HH:MM - HH:MM)',
                'is_required' => false,
            ],
            [
                'code' => 'dishwashing_fee',
                'name' => [
                    'vi' => 'Phụ thu rửa bát',
                    'en' => 'Dishwashing fee',
                ],
                'type' => 'number (VND)',
                'is_required' => false,
            ],
            [
                'code' => 'additional_rules',
                'name' => [
                    'vi' => 'Các nội quy khác',
                    'en' => 'Additional rules',
                ],
                'type' => 'Text',
                'is_required' => false,
            ],
        ];

        foreach ($houseRules as $rule) {
            $exists = HouseRule::where('code', $rule['code'])->exists();
            if (!$exists) {
                $insertData = $rule;
                $insertData['name'] = $rule['name'];
                HouseRule::create($insertData);
            }
        }
    }
}

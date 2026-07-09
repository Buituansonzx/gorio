<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\CheckoutInstructionType;
use Illuminate\Database\Seeder;

class CheckoutInstructionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'code' => 'collect_used_towels',
                'name' => [
                    'vi' => 'Thu gom khăn đã sử dụng',
                    'en' => 'Collect used towels',
                ],
            ],
            [
                'code' => 'take_out_trash',
                'name' => [
                    'vi' => 'Đổ rác',
                    'en' => 'Take out trash',
                ],
            ],
            [
                'code' => 'turn_off_devices',
                'name' => [
                    'vi' => 'Tắt hết các thiết bị',
                    'en' => 'Turn off all devices',
                ],
            ],
            [
                'code' => 'lock_doors',
                'name' => [
                    'vi' => 'Khoá cửa',
                    'en' => 'Lock doors',
                ],
            ],
            [
                'code' => 'return_keys',
                'name' => [
                    'vi' => 'Trả lại chìa khoá',
                    'en' => 'Return keys',
                ],
            ],
            [
                'code' => 'custom_request',
                'name' => [
                    'vi' => 'Yêu cầu bổ sung',
                    'en' => 'Custom request',
                ],
            ],
        ];
        foreach ($types as $type) {
            $exists = CheckoutInstructionType::where('code', $type['code'])->exists();
            if (!$exists) {
                CheckoutInstructionType::create([
                    'code' => $type['code'],
                    'name' => $type['name'],
                ]);
            }
        }
    }
}

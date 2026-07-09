<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\FixedCheckTime;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixedCheckTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $current = Carbon::now();

        $data = [
            [
                'code' => 'overnight',
                'name' => [
                    'vi' => 'Khung giờ qua đêm',
                    'en' => 'Overnight time frame',
                ],
                'description' => [
                    'vi' => 'Checkin 21:00 - Checkout 09:00',
                    'en' => 'Check-in 21:00 - Check-out 09:00',
                ],
            ],
            [
                'code' => 'std',
                'name' => [
                    'vi' => 'Khung giờ tiêu chuẩn',
                    'en' => 'Standard time frame',
                ],
                'description' => [
                    'vi' => 'Checkin 14:00 - Checkout 11:00',
                    'en' => 'Check-in 14:00 - Check-out 11:00',
                ],
            ],
            [
                'code' => 'day_time',
                'name' => [
                    'vi' => 'Khung giờ ban ngày',
                    'en' => 'Daytime time frame',
                ],
                'description' => [
                    'vi' => 'Checkin 09:00 - Checkout 21:00',
                    'en' => 'Check-in 09:00 - Check-out 21:00',
                ],
            ],
        ];

        foreach ($data as $item) {
            $exists = FixedCheckTime::where('code', $item['code'])->exists();

            if (!$exists) {
                FixedCheckTime::create([
                    'code' => $item['code'],
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'is_active' => true,
                    'created_at' => $current,
                    'updated_at' => $current,
                ]);
            }
        }
    }
}

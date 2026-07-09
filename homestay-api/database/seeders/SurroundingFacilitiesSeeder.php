<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\SurroundingFacility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurroundingFacilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $facilities_inside = [
            [
                'code' => 'garden',
                'name' => [
                    'vi' => 'Sân vườn',
                    'en' => 'Garden',
                ],
            ],
            [
                'code' => 'gym',
                'name' => [
                    'vi' => 'Gym',
                    'en' => 'Gym',
                ],
            ],
            [
                'code' => 'swimming_pool',
                'name' => [
                    'vi' => 'Bể bơi',
                    'en' => 'Swimming pool',
                ],
            ],
            [
                'code' => 'balcony',
                'name' => [
                    'vi' => 'Ban công',
                    'en' => 'Balcony',
                ],
            ],
            [
                'code' => 'private_dining',
                'name' => [
                    'vi' => 'Phòng ăn riêng',
                    'en' => 'Private dining room',
                ],
            ],
            [
                'code' => 'surrounding_view',
                'name' => [
                    'vi' => 'Cảnh quan xung quanh',
                    'en' => 'Surrounding landscape',
                ],
            ],
            [
                'code' => 'laundry_area',
                'name' => [
                    'vi' => 'Khu giặt sấy',
                    'en' => 'Laundry area',
                ],
            ],
            [
                'code' => 'working_room',
                'name' => [
                    'vi' => 'Phòng làm việc riêng',
                    'en' => 'Private workspace',
                ],
            ],
        ];

        foreach ($facilities_inside as $facility) {
            $exists = SurroundingFacility::where('code', $facility['code'])->exists();
            if (!$exists) {
                SurroundingFacility::create([
                    'code' => $facility['code'],
                    'name' => $facility['name']
                ]);
            }
        }



    }
}

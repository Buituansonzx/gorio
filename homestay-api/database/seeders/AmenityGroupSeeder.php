<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\AmenityGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenityGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $amenityGroups = [
            [
                'code' => 'bathroom',
                'name' => [
                    'vi' => 'Phòng tắm',
                    'en' => 'Bathroom',
                ],
            ],
            [
                'code' => 'bedroom_laundry',
                'name' => [
                    'vi' => 'Phòng ngủ & Giặt ủi',
                    'en' => 'Bedroom & Laundry',
                ],
            ],
            [
                'code' => 'entertainment',
                'name' => [
                    'vi' => 'Giải trí',
                    'en' => 'Entertainment',
                ],
            ],
            [
                'code' => 'family',
                'name' => [
                    'vi' => 'Phù hợp với gia đình',
                    'en' => 'Family Friendly',
                ],
            ],
            [
                'code' => 'cooling_heating',
                'name' => [
                    'vi' => 'Điều hòa & Sưởi',
                    'en' => 'Cooling & Heating',
                ],
            ],
            [
                'code' => 'safety',
                'name' => [
                    'vi' => 'An toàn',
                    'en' => 'Safety',
                ],
            ],
            [
                'code' => 'internet',
                'name' => [
                    'vi' => 'Internet',
                    'en' => 'Internet',
                ],
            ],
            [
                'code' => 'kitchen',
                'name' => [
                    'vi' => 'Bếp',
                    'en' => 'Kitchen',
                ],
            ],
            [
                'code' => 'location_feature',
                'name' => [
                    'vi' => 'Đặc điểm vị trí',
                    'en' => 'Location Features',
                ],
            ],
            [
                'code' => 'outdoor',
                'name' => [
                    'vi' => 'Ngoài trời',
                    'en' => 'Outdoor',
                ],
            ],
            [
                'code' => 'facilities_parking',
                'name' => [
                    'vi' => 'Chỗ đỗ xe',
                    'en' => 'Parking Facilities',
                ],
            ],
            [
                'code' => 'services',
                'name' => [
                    'vi' => 'Dịch vụ',
                    'en' => 'Services',
                ],
            ],
        ];

        foreach ($amenityGroups as $group) {
            AmenityGroup::firstOrCreate(
                ['code' => $group['code']],
                ['name' => $group['name']]
            );
        }
    }
}

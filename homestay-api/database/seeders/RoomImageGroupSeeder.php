<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\RoomImageGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomImageGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $imageGroups = [
            [
                'code' => 'living_room',
                'name' => [
                    'vi' => 'Phòng khách',
                    'en' => 'Living room',
                ],
            ],
            [
                'code' => 'bedroom',
                'name' => [
                    'vi' => 'Phòng ngủ',
                    'en' => 'Bedroom',
                ],
            ],
            [
                'code' => 'shared_bathroom',
                'name' => [
                    'vi' => 'Phòng tắm chung',
                    'en' => 'Shared bathroom',
                ],
            ],
            [
                'code' => 'private_bathroom',
                'name' => [
                    'vi' => 'Phòng tắm riêng',
                    'en' => 'Private bathroom',
                ],
            ],
            [
                'code' => 'kitchen',
                'name' => [
                    'vi' => 'Bếp',
                    'en' => 'Kitchen',
                ],
            ],
        ];

        foreach ($imageGroups as $group) {
            RoomImageGroup::firstOrCreate(
                ['code' => $group['code']],
                ['name' => $group['name']]
            );
        }

    }
}

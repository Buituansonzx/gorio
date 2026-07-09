<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Containers\SharedSection\Room\Models\Attribute;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $attributes = [
            [
                'code' => 'bedroom',
                'name' => [
                    'vi' => 'Phòng ngủ',
                    'en' => 'Bedroom',
                ],
            ],
            [
                'code' => 'living_room',
                'name' => [
                    'vi' => 'Phòng khách',
                    'en' => 'Living Room',
                ],
            ],
            [
                'code' => 'bed',
                'name' => [
                    'vi' => 'Giường ngủ',
                    'en' => 'Bed',
                ],
            ],
            [
                'code' => 'extra_mattress',
                'name' => [
                    'vi' => 'Đệm kê thêm tối đa',
                    'en' => 'Extra Mattress',
                ],
            ],
            [
                'code' => 'private_bathroom',
                'name' => [
                    'vi' => 'Phòng tắm riêng',
                    'en' => 'Private Bathroom',
                ],
            ],
            [
                'code' => 'shared_bathroom',
                'name' => [
                    'vi' => 'Phòng tắm chung',
                    'en' => 'Shared Bathroom',
                ],
            ],
            [
                'code' => 'kitchen',
                'name' => [
                    'vi' => 'Phòng bếp',
                    'en' => 'Kitchen',
                ],
            ],
        ];
        foreach ($attributes as $attribute) {
            $exists = Attribute::where('code', $attribute['code'])->exists();
            if (!$exists) {
                Attribute::create([
                    'code' => $attribute['code'],
                    'name' => $attribute['name'],
                ]);
            }
        }
    }
}

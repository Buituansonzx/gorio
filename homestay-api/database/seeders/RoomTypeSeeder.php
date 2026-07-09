<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'code' => 'home',
                'name' => [
                    'vi' => 'Nhà Riêng',
                    'en' => 'Private House',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
            [
                'code' => 'apartment',
                'name' => [
                    'vi' => 'Căn Hộ/Chung Cư',
                    'en' => 'Apartment/Condominium',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
            [
                'code' => 'villa',
                'name' => [
                    'vi' => 'Villa',
                    'en' => 'Villa',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
            [
                'code' => 'resort',
                'name' => [
                    'vi' => 'Resort',
                    'en' => 'Resort',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
            [
                'code' => 'bungalow',
                'name' => [
                    'vi' => 'Bungalow',
                    'en' => 'Bungalow',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
            [
                'code' => 'farmstay',
                'name' => [
                    'vi' => 'Farmstay',
                    'en' => 'Farmstay',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
            [
                'code' => 'hotel',
                'name' => [
                    'vi' => 'Khách Sạn',
                    'en' => 'Hotel',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
            [
                'code' => 'camping',
                'name' => [
                    'vi' => 'Khu Cắm Trại',
                    'en' => 'Camping Area',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
            [
                'code' => 'tree_house',
                'name' => [
                    'vi' => 'Nhà Trên Cây',
                    'en' => 'Tree House',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
            [
                'code' => 'service_apartment',
                'name' => [
                    'vi' => 'Tòa Dịch Vụ',
                    'en' => 'Service Apartment',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
            [
                'code' => 'penthouse',
                'name' => [
                    'vi' => 'Penthouse/Duplex/Loft',
                    'en' => 'Penthouse/Duplex/Loft',
                ],
                'description' => [
                    'vi' => '',
                    'en' => '',
                ],
            ],
        ];

        foreach ($roomTypes as $type) {
            $exists = RoomType::where('code', $type['code'])->exists();
            if (!$exists) {
                RoomType::create([
                    'code' => $type['code'],
                    'name' => $type['name'],
                    'description' => $type['description'],
                ]);
            }
        }
    }
}

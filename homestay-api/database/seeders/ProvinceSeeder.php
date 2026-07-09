<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $provinces = [
            [
                'code' => 'HN',
                'name' => [
                    'vi' => 'Hà Nội',
                    'en' => 'Hanoi',
                ],
            ],
            [
                'code' => 'HP',
                'name' => [
                    'vi' => 'Hải Phòng',
                    'en' => 'Hai Phong',
                ],
            ],
            [
                'code' => 'DN',
                'name' => [
                    'vi' => 'Đà Nẵng',
                    'en' => 'Da Nang',
                ],
            ],
        ];

        foreach ($provinces as $province) {
            $exists = Province::where('code', $province['code'])->exists();
            if (!$exists) {
                Province::create([
                    'code' => $province['code'],
                    'name' => $province['name']
                ]);
            }
        }
    }
}

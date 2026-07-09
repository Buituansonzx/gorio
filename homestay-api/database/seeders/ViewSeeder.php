<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\View;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $views = [
            [
                'code' => 'city_view',
                'name' => [
                    'vi' => 'Thành Phố',
                    'en' => 'City view',
                ],
            ],
            [
                'code' => 'sea_view',
                'name' => [
                    'vi' => 'Biển',
                    'en' => 'Sea view',
                ],
            ],
            [
                'code' => 'garden_view',
                'name' => [
                    'vi' => 'Sân vườn',
                    'en' => 'Garden view',
                ],
            ],
            [
                'code' => 'lake_view',
                'name' => [
                    'vi' => 'Sông/Hồ',
                    'en' => 'Lake view',
                ],
            ],
            [
                'code' => 'mountain_view',
                'name' => [
                    'vi' => 'Núi/Đồi',
                    'en' => 'Mountain view',
                ],
            ],
            [
                'code' => 'forest_view',
                'name' => [
                    'vi' => 'Rừng cây',
                    'en' => 'Forest view',
                ],
            ]
        ];


        foreach ($views as $view) {
            $exists = View::where('code', $view['code'])->exists();
            if (!$exists) {
                View::create([
                    'code' => $view['code'],
                    'name' => $view['name']
                ]);
            }
        }
    }
}

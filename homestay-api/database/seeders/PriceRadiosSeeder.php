<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\PriceRatio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceRadiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $priceRadios = [
            [
                'code' => 'equal',
                'name' => [
                    'vi' => 'Bằng giá qua đêm',
                    'en' => 'Same as overnight price',
                ],
                'ratio' => 1,
            ],
            [
                'code' => 'one_point_five',
                'name' => [
                    'vi' => 'Gấp 1.5 giá qua đêm',
                    'en' => '1.5x overnight price',
                ],
                'ratio' => 1.5,
            ],
            [
                'code' => 'double',
                'name' => [
                    'vi' => 'Gấp 2 giá qua đêm',
                    'en' => '2x overnight price',
                ],
                'ratio' => 2,
            ],
        ];

        foreach ($priceRadios as $priceRadio) {
            $exists = PriceRatio::where('code', $priceRadio['code'])->exists();
            if (!$exists) {
                PriceRatio::create([
                    'code' => $priceRadio['code'],
                    'name' => $priceRadio['name'],
                    'ratio' => $priceRadio['ratio'],
                ]);
            }
        }
    }
}

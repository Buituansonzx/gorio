<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\SupportContact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportContactSeeder extends Seeder
{
    public function run(): void
    {
        $infos = [
            [
                'label' => 'zalo',
                'value' => 'https://zalo.me/0327032207',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'facebook',
                'value' => 'https://www.facebook.com/gorio.vn',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'hotline',
                'value' => '0327032207',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'email',
                'value' => 'gorio.support@gmail.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($infos as $info) {
            SupportContact::firstOrCreate($info);
        }
    }
}

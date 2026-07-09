<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\Ota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $otas = [
            ['name' => 'Booking.com', 'code' => 'BOOKING'],
            ['name' => 'Expedia', 'code' => 'EXPEDIA'],
            ['name' => 'Agoda', 'code' => 'AGODA'],
            ['name' => 'Airbnb', 'code' => 'AIRBNB'],
            ['name' => 'Hotels.com', 'code' => 'HOTELS'],
            ['name' => 'Hong Manage', 'code' => 'HONG_MANAGE'],
            ['name' => 'Dayladau' , 'code' => 'DAYLADAU'],
            ['name' => 'KiotViet' , 'code' => 'KIOTVIET'],
        ];

        foreach ($otas as $ota) {
            if(!Ota::where('code', $ota['code'])->exists()) {
                Ota::create($ota);
            }
        }
    }
}

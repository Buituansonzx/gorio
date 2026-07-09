<?php

namespace Database\Seeders;

use App\Containers\SharedSection\OtpDevice\Models\OtpDevice;
use Illuminate\Database\Seeder;
use Log;

class OtpDevicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = [
            [
                'device_name' => 'Itel P55+',
                'phone_number' => '+84865941824',
                'is_active' => true,
            ],
            [
                'device_name' => 'Redmi 15C',
                'phone_number' => '+84345116371',
                'is_active' => true,
            ],
        ];

        foreach ($devices as $device) {
            if(OtpDevice::where('phone_number', $device['phone_number'])->doesntExist()) {
                OtpDevice::create($device);
            }
        }
    }
}

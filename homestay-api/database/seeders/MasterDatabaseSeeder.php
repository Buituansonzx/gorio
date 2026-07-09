<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AmenityGroupSeeder::class,
            AmenitiesSeeder::class,
            AttributesSeeder::class,
            CheckinMethodSeeder::class,
            CheckoutInstructionTypeSeeder::class,
            ProvinceSeeder::class,
            DistrictSeeder::class,
            FixedCheckTimeSeeder::class,
            HouseRuleSeeder::class,
            RoomAccessTypeSeeder::class,
            RoomImageGroupSeeder::class,
            RoomTypeSeeder::class,
            SurroundingFacilitiesSeeder::class,
            ViewSeeder::class
        ]);
    }
}

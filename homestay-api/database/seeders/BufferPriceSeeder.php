<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BufferPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('room_fixed_check_time')->update([
            'mon_buffer_price' => 10000,
            'tue_buffer_price' => 10000,
            'wed_buffer_price' => 10000,
            'thu_buffer_price' => 10000,
            'fri_buffer_price' => 10000,
            'sat_buffer_price' => 0,
            'sun_buffer_price' => 10000,
        ]);
        DB::table('room_hourly_pricing')->update([
            'buffer_price' => 20000,
        ]);
        DB::table('room_combo_pricing')->update([
            'mon_buffer_price' => 20000,
            'tue_buffer_price' => 20000,
            'wed_buffer_price' => 20000,
            'thu_buffer_price' => 20000,
            'fri_buffer_price' => 20000,
            'sat_buffer_price' => 20000,
            'sun_buffer_price' => 20000,
        ]);
    }
}

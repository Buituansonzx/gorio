<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

class SettingSeeder extends ParentSeeder
{
    public function run(): void
    {
        $exists = DB::table('setting')->where('key', 'check_phone_support_status')->exists();
        if (!$exists) {
            DB::table('setting')->insert([
                'key' => 'check_phone_support_status',
                'value' => '0',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

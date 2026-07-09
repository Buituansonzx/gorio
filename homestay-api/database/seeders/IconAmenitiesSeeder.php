<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IconAmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = Storage::disk('public')->files('icon/amenity');
        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            DB::table('amenities')
                ->where('code', $filename)
                ->update([
                    'icon' => $file,
                ]);
        }
    }
}

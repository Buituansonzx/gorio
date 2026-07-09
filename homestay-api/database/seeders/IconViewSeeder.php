<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IconViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = Storage::disk('public')->files('icon/view');
        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            DB::table('views')
                ->where('code', $filename)
                ->update([
                    'icon_url' => $file,
                ]);
        }
    }
}

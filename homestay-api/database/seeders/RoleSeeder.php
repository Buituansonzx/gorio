<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'guard_name' => 'api'],
            ['name' => 'user', 'guard_name' => 'api'],
            ['name' => 'host', 'guard_name' => 'api'],
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}

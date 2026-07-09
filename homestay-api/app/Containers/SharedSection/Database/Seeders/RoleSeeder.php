<?php

namespace App\Containers\SharedSection\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Super Admin',
                'accessible_domains' => ['admin', 'home', 'customer'],
            ],
            [
                'name' => 'home',
                'display_name' => 'Chủ homestay',
                'accessible_domains' => ['home', 'customer'],
            ],
            [
                'name' => 'customer',
                'display_name' => 'Khách hàng',
                'accessible_domains' => ['customer'],
            ],
        ];

        // Tạo roles cho tất cả guards (web và api)
        $guards = array_keys(config('auth.guards'));
        
        foreach ($roles as $roleData) {
            foreach ($guards as $guard) {
                Role::updateOrCreate(
                    [
                        'name' => $roleData['name'],
                        'guard_name' => $guard
                    ],
                    [
                        'display_name' => $roleData['display_name'],
                        'accessible_domains' => json_encode($roleData['accessible_domains'])
                    ]
                );
            }
        }
    }
}

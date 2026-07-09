<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Containers\AppSection\User\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $users = [
            ['name' => 'Nguyễn Minh Tuấn', 'email' => 'tuan.nguyen@example.com', 'gender' => 'male'],
            ['name' => 'Trần Thị Lan', 'email' => 'lan.tran@example.com', 'gender' => 'female'],
            ['name' => 'Lê Văn Sơn', 'email' => 'son.le@example.com', 'gender' => 'male'],
            ['name' => 'Phạm Thùy Dung', 'email' => 'dung.pham@example.com', 'gender' => 'female'],
            ['name' => 'Đỗ Anh Đức', 'email' => 'duc.do@example.com', 'gender' => 'male'],
            ['name' => 'Bùi Hồng Nhung', 'email' => 'nhung.bui@example.com', 'gender' => 'female'],
            ['name' => 'Hoàng Quốc Huy', 'email' => 'huy.hoang@example.com', 'gender' => 'male'],
            ['name' => 'Vũ Ngọc Mai', 'email' => 'mai.vu@example.com', 'gender' => 'female'],
            ['name' => 'Ngô Thanh Bình', 'email' => 'binh.ngo@example.com', 'gender' => 'male'],
            ['name' => 'Phan Thảo Vy', 'email' => 'vy.phan@example.com', 'gender' => 'female'],
        ];

        foreach ($users as $index => $u) {
            $phone = '09' . rand(10000000, 99999999);

            $nameParts = explode(' ', $u['name']);
            $firstName = end($nameParts);
            $lastName = $nameParts[0];
            $birth = Carbon::now()
                ->subYears(rand(20, 40))
                ->subDays(rand(0, 365));

            User::create([
                'name' => $u['name'],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $u['email'],
                'phone' => $phone,
                'country_code' => 'VN',
                'phone_code' => '84',
                'phone_number' => $phone,
                'password' => Hash::make('password'),
                'gender' => $u['gender'],
                'birth' => $birth,
                'email_verified_at' => $now,
                'status' => User::STATUS_ACTIVE,
                'is_blocked' => false,
            ]);
        }
    }
}

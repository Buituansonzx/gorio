<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Room\Models\District;
use App\Containers\SharedSection\Room\Models\Province;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $districts = [
            ['code' => 'hn_badinh', 'vi' => 'Ba Đình', 'en' => 'Ba Dinh'],
            ['code' => 'hn_hoankiem', 'vi' => 'Hoàn Kiếm', 'en' => 'Hoan Kiem'],
            ['code' => 'hn_tayho', 'vi' => 'Tây Hồ', 'en' => 'Tay Ho'],
            ['code' => 'hn_longbien', 'vi' => 'Long Biên', 'en' => 'Long Bien'],
            ['code' => 'hn_caugiay', 'vi' => 'Cầu Giấy', 'en' => 'Cau Giay'],
            ['code' => 'hn_dongda', 'vi' => 'Đống Đa', 'en' => 'Dong Da'],
            ['code' => 'hn_haibatrung', 'vi' => 'Hai Bà Trưng', 'en' => 'Hai Ba Trung'],
            ['code' => 'hn_hoangmai', 'vi' => 'Hoàng Mai', 'en' => 'Hoang Mai'],
            ['code' => 'hn_thanhxuan', 'vi' => 'Thanh Xuân', 'en' => 'Thanh Xuan'],
            ['code' => 'hn_socson', 'vi' => 'Sóc Sơn', 'en' => 'Soc Son'],
            ['code' => 'hn_donganh', 'vi' => 'Đông Anh', 'en' => 'Dong Anh'],
            ['code' => 'hn_gialam', 'vi' => 'Gia Lâm', 'en' => 'Gia Lam'],
            ['code' => 'hn_namtuliem', 'vi' => 'Nam Từ Liêm', 'en' => 'Nam Tu Liem'],
            ['code' => 'hn_bactuliem', 'vi' => 'Bắc Từ Liêm', 'en' => 'Bac Tu Liem'],
            ['code' => 'hn_thanhtri', 'vi' => 'Thanh Trì', 'en' => 'Thanh Tri'],
            ['code' => 'hn_melinh', 'vi' => 'Mê Linh', 'en' => 'Me Linh'],
            ['code' => 'hn_hadong', 'vi' => 'Hà Đông', 'en' => 'Ha Dong'],
            ['code' => 'hn_sontay', 'vi' => 'Sơn Tây', 'en' => 'Son Tay'],
            ['code' => 'hn_bavi', 'vi' => 'Ba Vì', 'en' => 'Ba Vi'],
            ['code' => 'hn_phuctho', 'vi' => 'Phúc Thọ', 'en' => 'Phuc Tho'],
            ['code' => 'hn_danphuong', 'vi' => 'Đan Phượng', 'en' => 'Dan Phuong'],
            ['code' => 'hn_hoaiduc', 'vi' => 'Hoài Đức', 'en' => 'Hoai Duc'],
            ['code' => 'hn_quocoai', 'vi' => 'Quốc Oai', 'en' => 'Quoc Oai'],
            ['code' => 'hn_thachthat', 'vi' => 'Thạch Thất', 'en' => 'Thach That'],
            ['code' => 'hn_chuongmy', 'vi' => 'Chương Mỹ', 'en' => 'Chuong My'],
            ['code' => 'hn_thanhoai', 'vi' => 'Thanh Oai', 'en' => 'Thanh Oai'],
            ['code' => 'hn_thuongtin', 'vi' => 'Thường Tín', 'en' => 'Thuong Tin'],
            ['code' => 'hn_phuxuyen', 'vi' => 'Phú Xuyên', 'en' => 'Phu Xuyen'],
            ['code' => 'hn_unghoa', 'vi' => 'Ứng Hòa', 'en' => 'Ung Hoa'],
            ['code' => 'hn_myduc', 'vi' => 'Mỹ Đức', 'en' => 'My Duc'],
        ];

        foreach ($districts as $district) {
            $exists = District::where('code', $district['code'])->exists();
            $provinceId = Province::where('code', 'HN')->value('id');
            if (!$exists) {
                District::create([
                    'code' => $district['code'],
                    'province_id' => $provinceId,
                    'name' => ['vi' => $district['vi'], 'en' => $district['en']],
                ]);
            }
        }
    }
}

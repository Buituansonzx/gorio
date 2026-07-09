<?php

namespace Database\Seeders;

use App\Containers\SharedSection\Order\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherScheduledSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'RETURN50K',
                'is_scheduled' => true,
                'name' => '🎁 QUÀ QUAY LẠI DÀNH RIÊNG CHO BẠN',
                'type' => Voucher::TYPE_VOUCHER_PUBLIC,
                'description' => "Cảm ơn bạn đã trải nghiệm cùng Gorio 💙 \nGiảm ngay 50K cho lần đặt tiếp theo",
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'quantity' => 1000000,
                'usage_limit' => 1000,
                'is_active' => true,
                'condition_apply' => "Dành cho người dùng đã hoàn thành ít nhất 1 đơn đặt phòng trên Gorio.
Voucher được cấp sau khi chuyến đi hoàn tất.
Có hiệu lực trong vòng 14 ngày kể từ ngày nhận.
Mỗi voucher sử dụng 1 lần duy nhất.
Không áp dụng đồng thời với các voucher khác.
Áp dụng cho các phòng đủ điều kiện trên hệ thống.
Không quy đổi thành tiền mặt."
            ],
            [
                'code' => 'FRIDAY60K',
                'is_scheduled' => true,
                'name' => '🔥 HOT DEAL FRIDAY',
                'type' => Voucher::TYPE_VOUCHER_PUBLIC,
                'description' => "Deal cuối tuần đã mở!\nGiảm 60K khi đặt phòng hôm nay",
                'discount_type' => 'fixed',
                'discount_value' => 60000,
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'quantity' => 1000000,
                'usage_limit' => 1000,
                'is_active' => true,
                'condition_apply' => "Áp dụng cho các đơn đặt phòng được thực hiện vào ngày Thứ 6.
Thời gian hiệu lực: 24 giờ kể từ khi chương trình bắt đầu.
Áp dụng cho mọi thời gian check-in (không giới hạn ngày nhận phòng).
Mỗi tài khoản sử dụng 1 lần trong thời gian diễn ra chương trình.
Áp dụng cho các phòng đủ điều kiện trên Gorio.
Không áp dụng đồng thời với các voucher khác.
Chương trình có thể kết thúc sớm khi đạt số lượng giới hạn (nếu có)."
            ],
            [
                'code' => 'SATURDAY70K',
                'is_scheduled' => true,
                'name' => '🚨 SATURDAY SUPER DEAL',
                'type' => Voucher::TYPE_VOUCHER_PUBLIC,
                'description' => "Deal nóng cuối tuần 🔥\nGiảm ngay 70K — chỉ trong hôm nay!",
                'discount_type' => 'fixed',
                'discount_value' => 70000,
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'quantity' => 1000000,
                'usage_limit' => 1000,
                'is_active' => true,
                'condition_apply' => "Áp dụng cho các đơn đặt phòng được thực hiện vào ngày Thứ 7.
Thời gian hiệu lực: 12 giờ kể từ khi chương trình bắt đầu.
Áp dụng cho mọi thời gian check-in.
Mỗi tài khoản sử dụng 1 lần trong thời gian diễn ra chương trình.
Áp dụng cho các phòng đủ điều kiện.
Không áp dụng đồng thời với các voucher khác.
Có thể giới hạn số lượng voucher và kết thúc sớm khi hết lượt."
            ],
            [
                'code' => "100KLANDAU",
                'condition_apply' => "Áp dụng cho người dùng lần đầu đặt phòng trên Gorio.
Mỗi tài khoản chỉ sử dụng 1 lần.
Áp dụng cho tất cả phòng đủ điều kiện trên nền tảng.
Không áp dụng đồng thời với các voucher khác.
Không quy đổi thành tiền mặt.
Gorio có quyền điều chỉnh hoặc kết thúc chương trình khi cần thiết."
            ]
        ];

        foreach ($vouchers as $voucherData) {
            if($voucherData['code'] == "100KLANDAU"){
                if(!Voucher::where('code', $voucherData['code'])->exists()){
                    continue;
                }
            }
            Voucher::updateOrCreate(
                [
                    'code' => $voucherData['code'],
                ],
                $voucherData
            );
        }

    }
}

<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Console\Command;

class UpdateAddressRoomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-address-room-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mapping = [
            '32 ngõ 58 Nguyễn Khánh Toàn' => 'Số 9 ngách 32 ngõ 58 Nguyễn Khánh Toàn',
            '24 ngõ 79 Dương Quảng Hàm' => 'Số 8B ngách 24 ngõ 79 Dương Quảng Hàm',
            '7 ngõ 690 Lạc Long Quân' => 'số 5 ngách 7 ngõ 690 Lạc Long Quân',
            '20 ngách 31 ngõ 135 Đội Cấn' => 'Số 20 ngách 31 ngõ 135 Đội Cấn',
            '6 ngõ 59 Mễ Trì' => 'Số 6 ngõ 59 Mễ Trì',
            '32 ngõ 221 Tôn Đức Thắng' => 'Số 12 ngách 32 ngõ 221 Tôn Đức Thắng',
            '35 ngõ 52 Quan Nhân' => 'Số 35 ngõ 52 Quan Nhân',
            '1 ngách 21 ngõ 84 Kim Ngưu' => 'Số 1 ngách 20 ngõ 84 Kim Ngưu',
            '91 ngõ 176 Trương Định' => 'Số 91 ngõ 176 Trương Định',
            'Ngõ 374 Âu Cơ' => 'Số 82a ngách 264/15 ngõ 374 Âu Cơ',
            'Ngõ 89 Phan Kế Bính' => 'Số 72-74 hẻm 89/36/2 Phan Kế Bính'
        ];
        foreach ($mapping as $oldAddress => $newAddress) {
            Room::where('address', $oldAddress)
                ->update(['address' => $newAddress]);
        }
    }
}

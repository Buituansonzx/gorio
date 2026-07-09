<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\FixedCheckTime;
use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportRoomFixedCheckTimeTask extends ParentTask
{
    public function __construct()
    {
    }

    public function run($files)
    {
        foreach ($files as $file) {
            if (!$file->isValid()) {
                throw new \Exception('Invalid file upload.');
            }

            $rows = Excel::toArray([], $file)[0];

            unset($rows[0]);
                foreach ($rows as $row) {
                    $roomCode = trim($row[0] ?? null); // Mã phòng
                    $fixedCheckTimeCode = trim($row[1] ?? null); // Mã thời gian check-in cố định
                    $price = trim($row[2] ?? null); // Giá phòng
                    $monPrice = trim($row[3] ?? null); // Giá phòng thứ 2 trong tuần
                    $tuePrice = trim($row[4] ?? null); // Giá phòng thứ 3 trong tuần
                    $wedPrice = trim($row[5] ?? null); // Giá phòng thứ 4 trong tuần
                    $thuPrice = trim($row[6] ?? null); // Giá phòng thứ 5 trong tuần
                    $friPrice = trim($row[7] ?? null); // Giá phòng thứ 6 trong tuần
                    $satPrice = trim($row[8] ?? null); // Giá phòng thứ 7 trong tuần
                    $sunPrice = trim($row[9] ?? null); // Giá phòng chủ nhật
                    $phoneNumberHost = trim($row[10] ?? null); // Số điện thoại host

                    $host = Host::where('hotline', $phoneNumberHost)->first();
                    if (!$host) {
                        continue;
                    }

                    $room = Room::where('host_id', $host->id)
                        ->whereRaw('LOWER(name) = ?', [mb_strtolower($roomCode)])
                        ->first();                    if (!$room) {
                        continue; // Bỏ qua nếu không tìm thấy phòng
                    }
                    $fixedCheckTime = FixedCheckTime::where('code', $fixedCheckTimeCode)->first();
                    if (!$fixedCheckTime) {
                        continue; // Bỏ qua nếu không tìm thấy thời gian check-in cố định
                    }
                    $room->fixedCheckTime()->attach($fixedCheckTime->id, [
                        'price'     => $price,
                        'mon_price' => $monPrice,
                        'mon_buffer_price' => 10000,
                        'tue_price' => $tuePrice,
                        'tue_buffer_price' => 10000,
                        'wed_price' => $wedPrice,
                        'wed_buffer_price' => 10000,
                        'thu_price' => $thuPrice,
                        'thu_buffer_price' => 10000,
                        'fri_price' => $friPrice,
                        'fri_buffer_price' => 10000,
                        'sat_price' => $satPrice,
                        'sat_buffer_price' => 10000,
                        'sun_price' => $sunPrice,
                        'sun_buffer_price' => 10000,
                    ]);

                }
        }
    }
}

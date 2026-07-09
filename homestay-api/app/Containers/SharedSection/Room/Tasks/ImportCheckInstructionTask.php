<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\CheckinMethod;
use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportCheckInstructionTask extends ParentTask
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
                    $roomCode  = trim($row[0] ?? null); // Mã phòng
                    $checkinInstructionCode = trim($row[1] ?? null); // Mã hướng dẫn check-in
                    $wayToHouse = trim($row[2] ?? null); // Cách vào nhà
                    $direction = trim($row[3] ?? null); // Hướng dẫn chỉ đường
                    $wifiName = trim($row[4] ?? null); // Tên wifi
                    $wifiPassword = trim($row[5] ?? null); // Mật khẩu wifi
                    $checkoutCodesString = trim($row[6] ?? null); // Hướng dẫn check-out
                    $phoneNumberHost = trim($row[7] ?? null); // Số điện thoại host
                    $host = Host::whereHas('user', function ($q) use ($phoneNumberHost) {
                        $q->where('phone_number', $phoneNumberHost);
                    })->first();
                    if (!$host) {
                        continue;
                    }

                    $room = Room::where('host_id', $host->id)
                        ->whereRaw('LOWER(name) = ?', [mb_strtolower($roomCode)])
                        ->first();                    if (!$room) {
                        continue; // Bỏ qua nếu không tìm thấy phòng
                    }
                    $checkinInstruction = CheckinMethod::where('code', $checkinInstructionCode)->first();
                    if (!$checkinInstruction) {
                        continue; // Bỏ qua nếu không tìm thấy hướng dẫn check-in
                    }
                    $room->checkInstructions()->attach($checkinInstruction->id, [
                        'way_to_house_message'          => $wayToHouse,
                        'directions_message'             => $direction,
                        'wifi_name'             => $wifiName,
                        'wifi_password'         => $wifiPassword,
                    ]);

                    $checkoutCodes = array_map('trim', explode(',', $checkoutCodesString));
                    foreach ($checkoutCodes as $checkoutCode) {
                        $room->checkoutInstructionType()
                            ->attach($checkinInstruction->id);
                    }
                }

        }
    }
}

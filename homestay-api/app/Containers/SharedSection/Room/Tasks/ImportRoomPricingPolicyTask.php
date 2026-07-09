<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomPricingPolicy;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportRoomPricingPolicyTask extends ParentTask
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
                    $roomCode = trim($row[0] ?? '');
                    $extraAdultPrice = trim($row[1] ?? '');
                    $extraHourPrice = trim($row[2] ?? '');
                    $phoneNumberHost = trim($row[3] ?? '');

                    if ($roomCode === '' && $extraAdultPrice === '' && $extraHourPrice === '' && $phoneNumberHost === '') {
                        continue;
                    }

                    $host = Host::where('hotline', $phoneNumberHost)->first();
                    if (!$host) {
                        throw new \Exception("Host with hotline {$phoneNumberHost} not found.");
                    }

                    $room = Room::where('host_id', $host->id)
                        ->whereRaw('LOWER(name) = ?', [mb_strtolower($roomCode)])
                        ->first();
                    if (!$room) {
                       throw new \Exception("Room with code {$roomCode} not found.");
                    }

                    RoomPricingPolicy::create([
                        'room_id' => $room->id,
                        'extra_adult_price' => $extraAdultPrice !== '' ? $extraAdultPrice : null,
                        'extra_hour_price' => $extraHourPrice !== '' ? $extraHourPrice : null,
                    ]);
                }

        }

    }
}

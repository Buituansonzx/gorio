<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomHourlyPricing;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportRoomHourlyPricingTask extends ParentTask
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
                $roomCode = trim($row[0] ?? null);
                $minHours = trim($row[1] ?? null);
                $minHoursPrice = trim($row[2] ?? null);
                $nextHoursPrice = trim($row[3] ?? null);
                $phoneNumberHost = trim($row[4] ?? null);

                $host = Host::where('hotline', $phoneNumberHost)->first();
                if (!$host) {
                    continue;
                }

                $room = Room::where('host_id', $host->id)
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower($roomCode)])
                    ->first();                if (!$room) {
                    continue;
                }

                RoomHourlyPricing::create([
                    'room_id' => $room->id,
                    'min_hours' => $minHours !== '' ? (int)$minHours : null,
                    'min_hours_price' => $minHoursPrice !== '' ? $minHoursPrice : null,
                    'mon_min_hour_price' => $minHoursPrice !== '' ? $minHoursPrice : null,
                    'mon_buffer_price' => 20000,
                    'tue_min_hour_price' => $minHoursPrice !== '' ? $minHoursPrice : null,
                    'tue_buffer_price' => 20000,
                    'wed_min_hour_price' => $minHoursPrice !== '' ? $minHoursPrice : null,
                    'wed_buffer_price' => 20000,
                    'thu_min_hour_price' => $minHoursPrice !== '' ? $minHoursPrice : null,
                    'thu_buffer_price' => 20000,
                    'fri_min_hour_price' => $minHoursPrice !== '' ? $minHoursPrice : null,
                    'fri_buffer_price' => 20000,
                    'sat_min_hour_price' => $minHoursPrice !== '' ? $minHoursPrice : null,
                    'sat_buffer_price' => 20000,
                    'sun_min_hour_price' => $minHoursPrice !== '' ? $minHoursPrice : null,
                    'sun_buffer_price' => 20000,
                    'next_hour_price' => $nextHoursPrice !== '' ? $nextHoursPrice : null,
                    'is_active' => true,
                ]);
            }

        }

    }
}

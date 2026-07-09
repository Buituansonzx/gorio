<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomComboPricing;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportRoomComboPricingTask extends ParentTask
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
                    $startTime = trim($row[1] ?? null);
                    $endTime = trim($row[2] ?? null);
                    $price = trim($row[3] ?? null);
                    $mon_price = trim($row[4] ?? null);
                    $tue_price = trim($row[5] ?? null);
                    $wed_price = trim($row[6] ?? null);
                    $thu_price = trim($row[7] ?? null);
                    $fri_price = trim($row[8] ?? null);
                    $sat_price = trim($row[9] ?? null);
                    $sun_price = trim($row[10] ?? null);
                    $currency =  trim($row[11] ?? null);
                    $phoneNumberHost = trim($row[12] ?? null);


                    $host = Host::where('hotline', $phoneNumberHost)->first();
                    if (!$host) {
                        continue;
                    }

                    $room = Room::where('host_id', $host->id)
                        ->whereRaw('LOWER(name) = ?', [mb_strtolower($roomCode)])
                        ->first();
                    if (!$room) {
                        continue;
                    }

                    RoomComboPricing::updateOrCreate([
                        'room_id' => $room->id,
                        'start_time' => $startTime,
                        'end_time' => $endTime],
                        [
                        'price'      => $price,
                        'mon_price'  => filled($mon_price) ? $mon_price : null,
                        'mon_buffer_price' => 20000,
                        'tue_price'  => filled($tue_price) ? $tue_price : null,
                        'tue_buffer_price' => 20000,
                        'wed_price'  => filled($wed_price) ? $wed_price : null,
                        'wed_buffer_price' => 20000,
                        'thu_price'  => filled($thu_price) ? $thu_price : null,
                        'thu_buffer_price' => 20000,
                        'fri_price'  => filled($fri_price) ? $fri_price : null,
                        'fri_buffer_price' => 20000,
                        'sat_price'  => filled($sat_price) ? $sat_price : null,
                        'sat_buffer_price' => 40000,
                        'sun_price'  => filled($sun_price) ? $sun_price : null,
                        'sun_buffer_price' => 20000,
                        'currency'   => $currency,
                    ]);
                }
        }
    }
}

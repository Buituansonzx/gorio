<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomDiscountPricing;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportRoomDiscountPricingTask extends ParentTask
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
                    $discountType = trim($row[1] ?? null);
                    $preHours = trim($row[2] ?? null);
                    $percentDiscount = trim($row[3] ?? null);

                    $room = Room::where('code', $roomCode)->first();
                    if (!$room) {
                        throw new \Exception("Room with code {$roomCode} not found.");
                    }

                    RoomDiscountPricing::create([
                        'room_id' => $room->id,
                        'type' => $discountType,
                        'pre_hours' => $preHours,
                        'percent' => $percentDiscount,
                    ]);
                }
        }
    }
}

<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\RoomWeekdayPrice;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportRoomWeekdayPriceTask extends ParentTask
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
                    $policyId = trim($row[0] ?? null);
                    $weekday = trim($row[1] ?? null);
                    $price = trim($row[2] ?? null);

                    RoomWeekdayPrice::create([
                        'policy_id' => $policyId,
                        'weekday' => $weekday,
                        'price' => $price !== '' ? $price : null,
                    ]);
                }
        }
    }
}

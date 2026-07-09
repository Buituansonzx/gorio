<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\House;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomPolicy;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportRoomPolicyTask extends ParentTask
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
                    if (empty(array_filter($row, fn($cell) => trim((string)$cell) !== ''))) {
                        continue;
                    }
                    $houseName = trim($row[0] ?? null);
                    $policyType = trim($row[1] ?? null);
                    $content = trim($row[2] ?? null);

                    $house = House::where('name', $houseName)->first();
                    if (!$house) {
                        throw new \Exception("House with name {$houseName} not found.");
                    }
                    $rooms = Room::where('house_id', $house->id)->get();
                    foreach ($rooms as $room) {
                        if ($room->roomPolicy()->exists()) {
                            continue;
                        }
                        RoomPolicy::create([
                            'room_id' => $room->id,
                            'policy_type' => $policyType,
                            'content' => $content,
                        ]);
                    }
                }

        }

    }
}

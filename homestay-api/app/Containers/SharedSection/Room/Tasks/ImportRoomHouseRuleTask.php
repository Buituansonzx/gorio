<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\House;
use App\Containers\SharedSection\Room\Models\HouseRule;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomHouseRule;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportRoomHouseRuleTask extends ParentTask
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
                    $houseRuleCode = trim($row[1] ?? null);
                    $value = trim($row[2] ?? null);
                    
                    $house = House::where('name', $houseName)->first();
                    if (!$house) {
                        throw new \Exception("House with name {$houseName} not found.");
                    }
                    $rooms = $house->rooms;
                    $houseRule = HouseRule::where('code', $houseRuleCode)->first();
                    if (!$houseRule) {
                        throw new \Exception("House rule with code {$houseRuleCode} not found.");
                    }
                    foreach ($rooms as $room) {
                        if ($room->houseRule()->where('house_rule_id', $houseRule->id)->exists()) {
                            continue;
                        }
                        RoomHouseRule::create([
                            'room_id' => $room->id,
                            'house_rule_id' => $houseRule->id,
                            'value' => $value,
                        ]);
                    }

                }

        }

    }
}

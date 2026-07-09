<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\House;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomParkingRule;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportParkingRuleTask extends ParentTask
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
                    $vehicleType = trim($row[1] ?? null);
                    $isFree = trim($row[2] ?? null);
                    $price = trim($row[3] ?? null);
                    $currency = trim($row[4] ?? null);
                    $location = trim($row[5] ?? null);
                    $distanceMeters = trim($row[6] ?? null);
                    $description = trim($row[7] ?? null);
                    

                    $house = House::where('name', $houseName)->first();
                    if (!$house) {
                        throw new \Exception("House with name {$houseName} not found.");
                    }
                    $rooms = $house->rooms;
                    foreach ($rooms as $room) {
                        if ($room->parkingRules()->where('vehicle_type', $vehicleType)->exists()) {
                            continue;
                        }
                        RoomParkingRule::create([
                            'room_id' => $room->id,
                            'vehicle_type' => $vehicleType,
                            'is_free' => $isFree == '1' ? true : false,
                            'price' => $price !== '' ? $price : null,
                            'currency' => $currency,
                            'location' => $location,
                            'distance_meters' => $distanceMeters!== '' ? $distanceMeters : null,
                            'description' => $description,
                        ]);
                    }
                }
        }
    }
}

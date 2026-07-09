<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Amenity;
use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomHighlightAmenity;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportHighlightAmenityTask extends ParentTask
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
                    $amenityCode = trim($row[1] ?? null);
                    $serviceType = trim($row[2] ?? null);
                    $isFree = trim($row[3] ?? null);
                    $price = trim($row[4] ?? null);
                    $unit = trim($row[5] ?? null);
                    $phoneNumberHost = trim($row[6] ?? null);

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
                    $amenity = Amenity::where('code', $amenityCode)->first();
                    if (!$amenity) {
                        throw new \Exception("Amenity with code {$amenityCode} not found for room {$roomCode}.");
                    }
                    if ($room->highlightAmenities()->where('amenity_id', $amenity->id)->exists()) {
                        continue;
                    }
                    RoomHighlightAmenity::create([
                        'room_id' => $room->id,
                        'amenity_id' => $amenity->id,
                        'service_type' => $serviceType,
                        'is_free' => $isFree == '1' ? true : false,
                        'price' => $price !== '' ? $price : null,
                        'unit' => $unit,
                    ]);
                    // gán vào bảng trung gian nếu chưa có
                    if (!$room->amenities()->where('amenities.id', $amenity->id)->exists()) {
                        $room->amenities()->attach($amenity->id);
                    }
                }
        }
    }
}

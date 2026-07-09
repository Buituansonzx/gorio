<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Amenity;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomHighlightAmenity;
use App\Containers\SharedSection\Room\Models\RoomHighlightAmenityImage;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportHighlightAmenityImageTask extends ParentTask
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
                    $filePath = trim($row[2] ?? null);

                    $room = Room::where('code', $roomCode)->first();
                    if (!$room) {
                        throw new \Exception("Room with code {$roomCode} not found.");
                    }
                    $amenity = Amenity::where('code', $amenityCode)->first();
                    if (!$amenity) {
                        throw new \Exception("Amenity with code {$amenityCode} not found for room {$roomCode}.");
                    }
                    $highlightAmenity = RoomHighlightAmenity::where('room_id', $room->id)
                        ->where('amenity_id', $amenity->id)
                        ->first();
                    if (!$highlightAmenity) {
                        throw new \Exception("Highlight amenity for room {$roomCode} and amenity {$amenityCode} not found.");
                    }
                    RoomHighlightAmenityImage::create([
                        'room_highlight_amenity_id' => $highlightAmenity->id,
                        'file_path' => $filePath,
                    ]);
                }
        }
    }
}

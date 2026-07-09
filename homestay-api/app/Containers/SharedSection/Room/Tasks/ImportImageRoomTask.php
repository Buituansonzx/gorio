<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomImage;
use App\Containers\SharedSection\Room\Models\RoomImageGroup;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportImageRoomTask extends ParentTask
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
                    $filePath = trim($row[1] ?? null);
                    $areaCode = trim($row[2] ?? null);
                    $isCover = trim($row[3] ?? null);
                    $room = Room::where('code', $roomCode)->first();
                    if (!$room) {
                        throw new \Exception("Room with code {$roomCode} not found.");
                    }
                    $areaGroup = RoomImageGroup::where('code', $areaCode)->first();
                    if (!$areaGroup) {
                        throw new \Exception("Room area type with code {$areaCode} not found.");
                    }
                    RoomImage::create([
                        'room_id' => $room->id,
                        'file_path' => $filePath,
                        'room_image_area_group_id' => $areaGroup->id,
                        'is_cover' => $isCover == '1' ? true : false,
                        'order_index' => 1,
                    ]);
                }
        }
    }
}

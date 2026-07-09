<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomLock;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class BlockRoomTask extends ParentTask
{
    public function __construct()
    {
    }

    public function run($request, $roomId)
    {
        $room = Room::find($roomId);
        if(!$room){
            throw new \Exception("Room not found");
        }
        $roomLocked = RoomLock::create([
            'room_id' => $room->id,
            'type' => RoomLock::ADMIN_LOCK,
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time'],
        ]);
        return $roomLocked;
    }
}

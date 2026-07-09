<?php

namespace App\Containers\AdminSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateStatusRoomAction extends ParentAction
{
    public function run($roomId)
    {
        $room = Room::find($roomId);
        $room->is_active = !$room->is_active;
        $room->save();
        return $room;
    }
}

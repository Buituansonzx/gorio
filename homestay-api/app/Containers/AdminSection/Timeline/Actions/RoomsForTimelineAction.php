<?php

namespace App\Containers\AdminSection\Timeline\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Actions\Action as ParentAction;

final class RoomsForTimelineAction extends ParentAction
{
    public function run($hostId)
    {
        $rooms = Room::where('host_id', $hostId)
            ->select(['id', 'name'])
            ->get();
        return $rooms;
    }
}

<?php

namespace App\Containers\AdminSection\Room\Actions;

use App\Containers\ClientSection\Room\Models\Room;
use App\Ship\Parents\Actions\Action as ParentAction;
use Carbon\Carbon;

final class UpdateRoomOrderAction extends ParentAction
{
    public function run($request)
    {
        $rooms = Room::select('id', 'name')
            ->where('is_active', true)
            ->where('name', 'like', '%' . $request['search'] . '%')
            ->get();
        return $rooms;
    }
}

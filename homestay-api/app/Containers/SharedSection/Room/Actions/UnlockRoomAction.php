<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomLock;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UnlockRoomAction extends ParentAction
{
    public function run($roomLockId)
    {
        $roomLock = RoomLock::find($roomLockId);
        if ($roomLock) {
            $roomLock->delete();
        }
        return $roomLock;
    }
}

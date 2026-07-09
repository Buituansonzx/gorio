<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Tasks\FindRoomWithHostTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindRoomWithHostAction extends ParentAction
{
    public function __construct(
        private readonly FindRoomWithHostTask $findRoomWithHostTask,
    ) {
    }

    public function run(int $roomId): ?Room
    {
        return $this->findRoomWithHostTask->run($roomId);
    }
}

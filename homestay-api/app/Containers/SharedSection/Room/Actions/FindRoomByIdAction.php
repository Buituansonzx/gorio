<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Tasks\FindRoomByIdTask;
use App\Ship\Parents\Actions\Action as ParentAction;

class FindRoomByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindRoomByIdTask $findRoomByIdTask,
    ) {
    }

    public function run(string $id): Room
    {
        return $this->findRoomByIdTask->run($id);
    }
}

<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Tasks\CreateRoomTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateRoomAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomTask $createRoomTask,
    ) {
    }

    public function run(array $data): Room
    {
        return $this->createRoomTask->run($data);
    }
}

<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Tasks\UpdateRoomTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateRoomAction extends ParentAction
{
    public function __construct(
        private readonly UpdateRoomTask $updateRoomTask,
    ) {
    }

    public function run(array $data, int $id): Room
    {
        return $this->updateRoomTask->run($data, $id);
    }
}

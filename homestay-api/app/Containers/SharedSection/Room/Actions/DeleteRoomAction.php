<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\DeleteRoomTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteRoomAction extends ParentAction
{
    public function __construct(
        private readonly DeleteRoomTask $deleteRoomTask,
    ) {
    }

    public function run(int $id): bool
    {
        return $this->deleteRoomTask->run($id);
    }
}

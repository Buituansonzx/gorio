<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomType;
use App\Containers\SharedSection\Room\Tasks\CreateRoomTypeTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateRoomTypeAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomTypeTask $createRoomTypeTask,
    ) {
    }

    public function run(array $data): RoomType
    {
        return $this->createRoomTypeTask->run($data);
    }
}

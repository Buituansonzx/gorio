<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomAttribute;
use App\Containers\SharedSection\Room\Tasks\CreateRoomAttributeTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateRoomAttributeAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomAttributeTask $createRoomAttributeTask,
    ) {
    }

    public function run(array $data): RoomAttribute
    {
        return $this->createRoomAttributeTask->run($data);
    }
}

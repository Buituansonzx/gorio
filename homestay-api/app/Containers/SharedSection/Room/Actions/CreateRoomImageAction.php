<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomImage;
use App\Containers\SharedSection\Room\Tasks\CreateRoomImageTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateRoomImageAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomImageTask $createRoomImageTask,
    ) {
    }

    public function run(array $data): RoomImage
    {
        return $this->createRoomImageTask->run($data);
    }
}

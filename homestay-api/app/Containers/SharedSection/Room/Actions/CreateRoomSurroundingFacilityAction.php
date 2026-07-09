<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomSurroundingFacility;
use App\Containers\SharedSection\Room\Tasks\CreateRoomSurroundingFacilityTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateRoomSurroundingFacilityAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomSurroundingFacilityTask $createRoomSurroundingFacilityTask,
    ) {
    }

    public function run(array $data): RoomSurroundingFacility
    {
        return $this->createRoomSurroundingFacilityTask->run($data);
    }
}

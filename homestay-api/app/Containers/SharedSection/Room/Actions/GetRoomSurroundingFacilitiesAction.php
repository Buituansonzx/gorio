<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\GetRoomSurroundingFacilitiesTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomSurroundingFacilitiesAction extends ParentAction
{
    public function __construct(
        private readonly GetRoomSurroundingFacilitiesTask $getRoomSurroundingFacilitiesTask,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->getRoomSurroundingFacilitiesTask->run($roomId);
    }
}

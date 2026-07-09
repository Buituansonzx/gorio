<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomSurroundingFacilityRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetRoomSurroundingFacilitiesTask extends ParentTask
{
    public function __construct(
        private readonly RoomSurroundingFacilityRepository $repository,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->repository->findWhere(['room_id' => $roomId]);
    }
}

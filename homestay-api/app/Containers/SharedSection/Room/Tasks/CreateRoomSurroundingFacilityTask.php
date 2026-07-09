<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomSurroundingFacilityRepository;
use App\Containers\SharedSection\Room\Models\RoomSurroundingFacility;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateRoomSurroundingFacilityTask extends ParentTask
{
    public function __construct(
        private readonly RoomSurroundingFacilityRepository $repository,
    ) {
    }

    public function run(array $data): RoomSurroundingFacility
    {
        return $this->repository->create($data);
    }
}

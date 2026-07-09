<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomRepository;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateRoomTask extends ParentTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
    }

    public function run(array $data): Room
    {
        return $this->repository->create($data);
    }
}

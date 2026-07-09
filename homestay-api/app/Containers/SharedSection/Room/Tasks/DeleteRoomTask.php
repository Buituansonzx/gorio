<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteRoomTask extends ParentTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
    }

    public function run(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

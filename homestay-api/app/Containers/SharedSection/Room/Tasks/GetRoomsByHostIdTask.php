<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetRoomsByHostIdTask extends ParentTask
{
    public function __construct(
        private readonly RoomRepository $repository,
    ) {
    }

    public function run(int $hostId): Collection
    {
        return $this->repository->getRoomsByHostId($hostId);
    }
}

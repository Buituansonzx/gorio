<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomTypeRepository;
use App\Containers\SharedSection\Room\Models\RoomType;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateRoomTypeTask extends ParentTask
{
    public function __construct(
        private readonly RoomTypeRepository $repository,
    ) {
    }

    public function run(array $data): RoomType
    {
        return $this->repository->create($data);
    }
}

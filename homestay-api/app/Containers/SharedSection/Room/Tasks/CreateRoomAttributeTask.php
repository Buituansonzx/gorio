<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomAttributeRepository;
use App\Containers\SharedSection\Room\Models\RoomAttribute;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateRoomAttributeTask extends ParentTask
{
    public function __construct(
        private readonly RoomAttributeRepository $repository,
    ) {
    }

    public function run(array $data): RoomAttribute
    {
        return $this->repository->create($data);
    }
}

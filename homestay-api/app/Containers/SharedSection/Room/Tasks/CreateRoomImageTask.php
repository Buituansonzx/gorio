<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomImageRepository;
use App\Containers\SharedSection\Room\Models\RoomImage;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateRoomImageTask extends ParentTask
{
    public function __construct(
        private readonly RoomImageRepository $repository,
    ) {
    }

    public function run(array $data): RoomImage
    {
        return $this->repository->create($data);
    }
}

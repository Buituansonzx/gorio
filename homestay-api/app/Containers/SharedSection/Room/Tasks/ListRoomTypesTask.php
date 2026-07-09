<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomTypeRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Database\Eloquent\Collection;

class ListRoomTypesTask extends ParentTask
{
    public function __construct(
        private readonly RoomTypeRepository $repository,
    ) {
    }

    public function run(): Collection
    {
        return $this->repository->all();
    }
}

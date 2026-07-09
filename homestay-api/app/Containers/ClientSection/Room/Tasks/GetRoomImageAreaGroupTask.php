<?php

namespace App\Containers\ClientSection\Room\Tasks;

use App\Containers\ClientSection\Room\Data\Repositories\GetRoomImageAreaGroupRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetRoomImageAreaGroupTask extends ParentTask
{
    public function __construct(private readonly GetRoomImageAreaGroupRepository $repository)
    {
    }

    public function run()
    {
        return $this->repository->getRoomImageAreaGroups();
    }
}

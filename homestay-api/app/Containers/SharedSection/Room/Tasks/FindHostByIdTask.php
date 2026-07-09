<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\HostRepository;
use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindHostByIdTask extends ParentTask
{
    public function __construct(
        private readonly HostRepository $repository,
    ) {
    }

    public function run(int $id): Host
    {
        return $this->repository->find($id);
    }

    public function runWithRoomsCount(int $id): ?Host
    {
        return $this->repository->getHostWithRoomsCount($id);
    }
}

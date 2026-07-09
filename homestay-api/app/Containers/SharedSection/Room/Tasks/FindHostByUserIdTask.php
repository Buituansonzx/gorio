<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\HostRepository;
use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindHostByUserIdTask extends ParentTask
{
    public function __construct(
        private readonly HostRepository $repository,
    ) {
    }

    public function run(int $userId): ?Host
    {
        return $this->repository->findByUserId($userId);
    }
}

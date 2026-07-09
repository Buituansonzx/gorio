<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\HostRepository;
use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateHostTask extends ParentTask
{
    public function __construct(
        private readonly HostRepository $repository,
    ) {
    }

    public function run(array $data): Host
    {
        return $this->repository->create($data);
    }
}

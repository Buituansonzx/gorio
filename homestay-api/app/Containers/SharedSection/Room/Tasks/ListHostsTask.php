<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\HostRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListHostsTask extends ParentTask
{
    public function __construct(
        private readonly HostRepository $repository,
    ) {
    }

    public function run(): Collection
    {
        return $this->repository->paginate();
    }

    public function getVerifiedHosts(): Collection
    {
        return $this->repository->getVerifiedHosts();
    }

    public function getHostsWithActiveRooms(): Collection
    {
        return $this->repository->getHostsWithActiveRooms();
    }
}

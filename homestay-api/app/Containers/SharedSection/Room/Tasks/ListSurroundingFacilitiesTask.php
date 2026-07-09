<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\SurroundingFacilityRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListSurroundingFacilitiesTask extends ParentTask
{
    public function __construct(
        private readonly SurroundingFacilityRepository $repository,
    ) {
    }

    public function run(): Collection
    {
        return $this->repository->all();
    }
}

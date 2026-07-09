<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\SurroundingFacilityRepository;
use App\Containers\SharedSection\Room\Models\SurroundingFacility;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateSurroundingFacilityTask extends ParentTask
{
    public function __construct(
        private readonly SurroundingFacilityRepository $repository,
    ) {
    }

    public function run(array $data): SurroundingFacility
    {
        return $this->repository->create($data);
    }
}

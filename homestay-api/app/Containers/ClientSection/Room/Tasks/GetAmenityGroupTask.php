<?php

namespace App\Containers\ClientSection\Room\Tasks;

use App\Containers\ClientSection\Room\Data\Repositories\GetAmenityGroupRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetAmenityGroupTask extends ParentTask
{
    public function __construct(private readonly GetAmenityGroupRepository $repository )
    {

    }

    public function run()
    {
        return $this->repository->getAmenityGroups();
    }
}

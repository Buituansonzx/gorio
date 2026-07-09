<?php

namespace App\Containers\ClientSection\Room\Tasks;

use App\Containers\ClientSection\Room\Data\Repositories\GetProvinceRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetProvinceTask extends ParentTask
{
    public function __construct(private readonly GetProvinceRepository $repository)
    {
    }

    public function run()
    {
        return $this->repository->getProvinces();
    }
}

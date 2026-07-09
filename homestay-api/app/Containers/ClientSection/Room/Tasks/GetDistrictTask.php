<?php

namespace App\Containers\ClientSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\DistrictRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetDistrictTask extends ParentTask
{
    public function __construct(private readonly DistrictRepository $repository)
    {
    }

    public function run(string $provinceId)
    {
        return $this->repository->getDistricts($provinceId);
    }
}

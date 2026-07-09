<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\GetAmenitiesRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetAmenitiesTask extends ParentTask
{
    public function __construct(private readonly GetAmenitiesRepository $repository)
    {
    }

    public function run()
    {
        return $this->repository->getAmenities();
    }
}

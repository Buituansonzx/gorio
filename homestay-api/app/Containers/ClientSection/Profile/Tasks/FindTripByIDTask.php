<?php

namespace App\Containers\ClientSection\Profile\Tasks;

use App\Containers\ClientSection\Profile\Data\Repositories\GetTripsRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindTripByIDTask extends ParentTask
{
    public function __construct(private readonly GetTripsRepository $getTripsRepository)
    {
    }

    public function run($tripId)
    {
        return $this->getTripsRepository->findTripByID($tripId);
    }
}

<?php

namespace App\Containers\MobileSection\Profile\Tasks;

use App\Containers\MobileSection\Profile\Data\Repositories\TripsRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindTripByIDTask extends ParentTask
{
    public function __construct(private readonly TripsRepository $getTripsRepository)
    {
    }

    public function run($tripId)
    {
        return $this->getTripsRepository->findTripByID($tripId);
    }
}

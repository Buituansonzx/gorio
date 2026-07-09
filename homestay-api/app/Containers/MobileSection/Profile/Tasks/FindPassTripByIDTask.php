<?php

namespace App\Containers\MobileSection\Profile\Tasks;

use App\Containers\MobileSection\Profile\Data\Repositories\GetPassTripsRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindPassTripByIDTask extends ParentTask
{
    public function __construct(private readonly GetPassTripsRepository $getPassTripsRepository)
    {
    }

    public function run($passTripId)
    {
        return $this->getPassTripsRepository->findPassTripByID($passTripId);
    }
}

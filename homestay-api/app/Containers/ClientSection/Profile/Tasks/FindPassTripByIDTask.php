<?php

namespace App\Containers\ClientSection\Profile\Tasks;

use App\Containers\ClientSection\Profile\Data\Repositories\GetPassTripsRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\Auth;

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

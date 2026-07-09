<?php

namespace App\Containers\ClientSection\Profile\Actions;

use App\Containers\ClientSection\Profile\Tasks\GetTripsTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetTripsAction extends ParentAction
{
    public function __construct(private readonly GetTripsTask $getTripsTask)
    {

    }

    public function run(string $userId)
    {
        return $this->getTripsTask->run($userId);
    }
}

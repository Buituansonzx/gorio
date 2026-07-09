<?php

namespace App\Containers\MobileSection\Profile\Actions;

use App\Containers\MobileSection\Profile\Tasks\TripsTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class TripsAction extends ParentAction
{
    public function __construct(private readonly TripsTask $getTripsTask)
    {

    }

    public function run(string $userId)
    {
        return $this->getTripsTask->run($userId);
    }
}

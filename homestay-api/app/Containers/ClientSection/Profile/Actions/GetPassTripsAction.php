<?php

namespace App\Containers\ClientSection\Profile\Actions;

use App\Containers\ClientSection\Profile\Tasks\GetPassTripsTask;
use App\Containers\ClientSection\Profile\Tasks\GetProfileTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetPassTripsAction extends ParentAction
{
    public function __construct(private readonly GetPassTripsTask $getProfileTask)
    {

    }
    public function run(string $userId)
    {
        return $this->getProfileTask->run($userId);
    }
}

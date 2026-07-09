<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\GetAmenitiesTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetAmenitiesAction extends ParentAction
{
    public function __construct(private readonly GetAmenitiesTask $amenitiesTask)
    {
    }

    public function run()
    {
        return $this->amenitiesTask->run();
    }
}

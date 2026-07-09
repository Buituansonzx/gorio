<?php

namespace App\Containers\SharedSection\Profile\Actions;

use App\Containers\SharedSection\Profile\Tasks\GetListingRoomByHostIdTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetListingRoomByHostIdAction extends ParentAction
{

    public function __construct(private readonly GetListingRoomByHostIdTask $task)
    {
    }

    public function run(string $hostId)
    {
        return $this->task->run($hostId);
    }
}

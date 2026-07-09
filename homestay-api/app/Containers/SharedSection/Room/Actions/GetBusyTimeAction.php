<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\GetBusyTimeTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetBusyTimeAction extends ParentAction
{

    public function __construct(private readonly GetBusyTimeTask $getBusyTimeTask)
    {
    }

    public function run(string $roomId)
    {
        return $this->getBusyTimeTask->run($roomId);
    }
}

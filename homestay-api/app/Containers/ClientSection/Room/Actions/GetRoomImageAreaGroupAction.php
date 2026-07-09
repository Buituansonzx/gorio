<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\ClientSection\Room\Tasks\GetRoomImageAreaGroupTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomImageAreaGroupAction extends ParentAction
{

    public function __construct(private readonly GetRoomImageAreaGroupTask $task)
    {
    }

    public function run($request)
    {
        return $this->task->run();
    }

}

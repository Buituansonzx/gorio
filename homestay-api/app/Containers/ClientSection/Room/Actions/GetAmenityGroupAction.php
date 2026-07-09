<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\ClientSection\Room\Tasks\GetAmenityGroupTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetAmenityGroupAction extends ParentAction
{

    public function __construct(private readonly GetAmenityGroupTask $task)
    {
    }
    public function run($request)
    {
        return $this->task->run($request);
    }
}

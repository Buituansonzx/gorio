<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\ClientSection\Room\Tasks\GetProvinceTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetProvinceAction extends ParentAction
{
    public function __construct(private readonly GetProvinceTask $task)
    {
    }

    public function run()
    {
        return $this->task->run();
    }
}

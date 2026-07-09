<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\ClientSection\Room\Tasks\GetDistrictTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetDistrictAction extends ParentAction
{

    public function __construct(private readonly GetDistrictTask $task)
    {
    }

    public function run(string $provinceId)
    {
        return $this->task->run($provinceId);
    }

}

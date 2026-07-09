<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Tasks\FindHostByIdTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindHostByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindHostByIdTask $findHostByIdTask,
    ) {
    }

    public function run(int $id): Host
    {
        return $this->findHostByIdTask->run($id);
    }

    public function runWithRoomsCount(int $id): ?Host
    {
        return $this->findHostByIdTask->runWithRoomsCount($id);
    }
}

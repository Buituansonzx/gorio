<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Tasks\FindHostByUserIdTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindHostByUserIdAction extends ParentAction
{
    public function __construct(
        private readonly FindHostByUserIdTask $findHostByUserIdTask,
    ) {
    }

    public function run(int $userId): ?Host
    {
        return $this->findHostByUserIdTask->run($userId);
    }
}

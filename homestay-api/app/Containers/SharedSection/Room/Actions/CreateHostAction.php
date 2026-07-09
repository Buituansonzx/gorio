<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Tasks\CreateHostTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateHostAction extends ParentAction
{
    public function __construct(
        private readonly CreateHostTask $createHostTask,
    ) {
    }

    public function run(array $data): Host
    {
        return $this->createHostTask->run($data);
    }
}

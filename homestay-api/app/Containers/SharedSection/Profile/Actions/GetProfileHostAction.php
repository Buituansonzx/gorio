<?php

namespace App\Containers\SharedSection\Profile\Actions;

use App\Containers\SharedSection\Profile\Tasks\GetProfileHostTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetProfileHostAction extends ParentAction
{
    public function __construct(private readonly GetProfileHostTask $task)
    {
    }

    public function run(string $hostId)
    {
        return $this->task->run($hostId);
    }
}

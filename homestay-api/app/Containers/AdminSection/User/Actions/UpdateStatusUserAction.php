<?php

namespace App\Containers\AdminSection\User\Actions;

use App\Containers\AdminSection\User\Tasks\UpdateBlockedUserTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateStatusUserAction extends ParentAction
{
    public function __construct(private readonly UpdateBlockedUserTask $task)
    {
    }

    public function run($user)
    {
        return $this->task->run($user);
    }
}

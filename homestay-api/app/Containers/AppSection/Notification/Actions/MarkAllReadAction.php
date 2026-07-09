<?php

namespace App\Containers\AppSection\Notification\Actions;

use App\Containers\AppSection\Notification\Tasks\MarkAllReadTask;
use App\Containers\AppSection\Notification\Tasks\GetUnreadNotificationCountTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class MarkAllReadAction extends ParentAction
{

    public function __construct(
        private readonly MarkAllReadTask $task,
        private readonly GetUnreadNotificationCountTask $getUnreadCountTask
    ) {
    }

    public function run()
    {
        $this->task->run();
        return $this->getUnreadCountTask->run();
    }
}

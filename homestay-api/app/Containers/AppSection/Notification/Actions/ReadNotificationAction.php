<?php

namespace App\Containers\AppSection\Notification\Actions;

use App\Containers\AppSection\Notification\Tasks\ReadNotificationTask;
use App\Containers\AppSection\Notification\Tasks\GetUnreadNotificationCountTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;

final class ReadNotificationAction extends ParentAction
{
    public function __construct(
        private readonly ReadNotificationTask $task,
        private readonly GetUnreadNotificationCountTask $getUnreadCountTask
    ) {
    }

    public function run(Request $request)
    {
        $this->task->run($request->id);
        return $this->getUnreadCountTask->run();
    }
}

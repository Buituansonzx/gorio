<?php

namespace App\Containers\AppSection\Notification\Actions;

use App\Containers\AppSection\Notification\Tasks\GetUnreadNotificationCountTask;
use App\Ship\Parents\Actions\Action as ParentAction;

class GetUnreadNotificationCountAction extends ParentAction
{
    public function run(): int
    {
        return app(GetUnreadNotificationCountTask::class)->run();
    }
}

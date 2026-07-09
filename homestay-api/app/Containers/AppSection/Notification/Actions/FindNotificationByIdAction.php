<?php

namespace App\Containers\AppSection\Notification\Actions;

use App\Containers\AppSection\Notification\Models\Notification;
use App\Containers\AppSection\Notification\Tasks\FindNotificationByIdTask;
use App\Containers\AppSection\Notification\UI\API\Requests\FindNotificationByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindNotificationByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindNotificationByIdTask $findNotificationByIdTask,
    ) {
    }

    public function run(FindNotificationByIdRequest $request): Notification
    {
        return $this->findNotificationByIdTask->run($request->id);
    }
}

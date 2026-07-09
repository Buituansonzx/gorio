<?php

namespace App\Containers\AppSection\Notification\Actions;

use App\Containers\AppSection\Notification\Tasks\DeleteNotificationTask;
use App\Containers\AppSection\Notification\UI\API\Requests\DeleteNotificationRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteNotificationAction extends ParentAction
{
    public function __construct(
        private readonly DeleteNotificationTask $deleteNotificationTask,
    ) {
    }

    public function run(DeleteNotificationRequest $request): bool
    {
        return $this->deleteNotificationTask->run($request->id);
    }
}

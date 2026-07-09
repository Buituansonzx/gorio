<?php

namespace App\Containers\AppSection\Notification\Actions;

use App\Containers\AppSection\Notification\Models\Notification;
use App\Containers\AppSection\Notification\Tasks\UpdateNotificationTask;
use App\Containers\AppSection\Notification\UI\API\Requests\UpdateNotificationRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateNotificationAction extends ParentAction
{
    public function __construct(
        private readonly UpdateNotificationTask $updateNotificationTask,
    ) {
    }

    public function run(UpdateNotificationRequest $request): Notification
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->updateNotificationTask->run($data, $request->id);
    }
}

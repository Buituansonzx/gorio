<?php

namespace App\Containers\AppSection\Notification\Actions;

use App\Containers\AppSection\Notification\Models\Notification;
use App\Containers\AppSection\Notification\Tasks\CreateNotificationTask;
use App\Containers\AppSection\Notification\UI\API\Requests\CreateNotificationRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateNotificationAction extends ParentAction
{
    public function __construct(
        private readonly CreateNotificationTask $createNotificationTask,
    ) {
    }

    public function run(CreateNotificationRequest $request): Notification
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->createNotificationTask->run($data);
    }
}

<?php

namespace App\Containers\AppSection\Notification\Actions;

use App\Containers\AppSection\Notification\Tasks\ListNotificationsTask;
use App\Containers\AppSection\Notification\UI\API\Requests\ListNotificationsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListNotificationsAction extends ParentAction
{
    public function __construct(
        private readonly ListNotificationsTask $listNotificationsTask,
    ) {
    }

    public function run(ListNotificationsRequest $request): mixed
    {
        return $this->listNotificationsTask->run($request->validated());
    }
}

<?php

namespace App\Containers\AppSection\Notification\Tasks;

use App\Containers\AppSection\Notification\Data\Repositories\NotificationRepository;
use App\Containers\AppSection\Notification\Events\NotificationsListed;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListNotificationsTask extends ParentTask
{
    public function __construct(
        private readonly NotificationRepository $repository,
    ) {
    }

    public function run($request)
    {
        return $this->repository->listing($request);
    }
}

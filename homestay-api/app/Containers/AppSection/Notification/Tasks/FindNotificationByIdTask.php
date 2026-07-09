<?php

namespace App\Containers\AppSection\Notification\Tasks;

use App\Containers\AppSection\Notification\Data\Repositories\NotificationRepository;
use App\Containers\AppSection\Notification\Events\NotificationRequested;
use App\Containers\AppSection\Notification\Models\Notification;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindNotificationByIdTask extends ParentTask
{
    public function __construct(
        private readonly NotificationRepository $repository,
    ) {
    }

    public function run($id): Notification
    {
        $notification = $this->repository->findOrFail($id);

        NotificationRequested::dispatch($notification);

        return $notification;
    }
}

<?php

namespace App\Containers\AppSection\Notification\Tasks;

use App\Containers\AppSection\Notification\Data\Repositories\NotificationRepository;
use App\Containers\AppSection\Notification\Events\NotificationUpdated;
use App\Containers\AppSection\Notification\Models\Notification;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateNotificationTask extends ParentTask
{
    public function __construct(
        private readonly NotificationRepository $repository,
    ) {
    }

    public function run(array $data, $id): Notification
    {
        $notification = $this->repository->update($data, $id);

        NotificationUpdated::dispatch($notification);

        return $notification;
    }
}

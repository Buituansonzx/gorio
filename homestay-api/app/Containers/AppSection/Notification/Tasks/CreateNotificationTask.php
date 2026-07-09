<?php

namespace App\Containers\AppSection\Notification\Tasks;

use App\Containers\AppSection\Notification\Data\Repositories\NotificationRepository;
use App\Containers\AppSection\Notification\Events\NotificationCreated;
use App\Containers\AppSection\Notification\Models\Notification;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateNotificationTask extends ParentTask
{
    public function __construct(
        private readonly NotificationRepository $repository,
    ) {
    }

    public function run(array $data): Notification
    {
        $notification = $this->repository->create($data);

        NotificationCreated::dispatch($notification);

        return $notification;
    }
}

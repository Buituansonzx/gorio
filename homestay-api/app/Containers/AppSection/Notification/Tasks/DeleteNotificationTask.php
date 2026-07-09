<?php

namespace App\Containers\AppSection\Notification\Tasks;

use App\Containers\AppSection\Notification\Data\Repositories\NotificationRepository;
use App\Containers\AppSection\Notification\Events\NotificationDeleted;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteNotificationTask extends ParentTask
{
    public function __construct(
        private readonly NotificationRepository $repository,
    ) {
    }

    public function run($id): bool
    {
        $result = $this->repository->delete($id);

        NotificationDeleted::dispatch($result);

        return $result;
    }
}

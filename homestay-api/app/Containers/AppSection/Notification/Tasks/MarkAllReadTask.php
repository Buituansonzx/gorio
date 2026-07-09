<?php

namespace App\Containers\AppSection\Notification\Tasks;

use App\Containers\AppSection\Notification\Data\Repositories\NotificationRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class MarkAllReadTask extends ParentTask
{
    public function __construct(private readonly NotificationRepository $repository)
    {
    }

    public function run()
    {
        return $this->repository->markAllRead();
    }
}

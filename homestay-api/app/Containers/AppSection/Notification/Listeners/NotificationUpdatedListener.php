<?php

namespace App\Containers\AppSection\Notification\Listeners;

use App\Containers\AppSection\Notification\Events\NotificationUpdated;
use App\Ship\Parents\Listeners\Listener as ParentListener;
use Illuminate\Contracts\Queue\ShouldQueue;

final class NotificationUpdatedListener extends ParentListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function __invoke(NotificationUpdated $event): void
    {
    }
}

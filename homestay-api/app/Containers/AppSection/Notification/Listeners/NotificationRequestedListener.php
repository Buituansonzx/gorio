<?php

namespace App\Containers\AppSection\Notification\Listeners;

use App\Containers\AppSection\Notification\Events\NotificationRequested;
use App\Ship\Parents\Listeners\Listener as ParentListener;
use Illuminate\Contracts\Queue\ShouldQueue;

final class NotificationRequestedListener extends ParentListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function __invoke(NotificationRequested $event): void
    {
    }
}

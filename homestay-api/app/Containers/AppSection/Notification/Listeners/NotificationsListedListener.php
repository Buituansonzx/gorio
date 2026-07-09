<?php

namespace App\Containers\AppSection\Notification\Listeners;

use App\Containers\AppSection\Notification\Events\NotificationsListed;
use App\Ship\Parents\Listeners\Listener as ParentListener;
use Illuminate\Contracts\Queue\ShouldQueue;

final class NotificationsListedListener extends ParentListener implements ShouldQueue
{
    public function __construct()
    {
    }

    public function __invoke(NotificationsListed $event): void
    {
    }
}

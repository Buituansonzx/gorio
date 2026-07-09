<?php

namespace App\Containers\AppSection\Notification\Events;

use App\Containers\AppSection\Notification\Models\Notification;
use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;

final class NotificationCreated extends ParentEvent
{
    public function __construct(
        public readonly Notification $notification,
    ) {
    }

    /**
     * @return Channel[]
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}

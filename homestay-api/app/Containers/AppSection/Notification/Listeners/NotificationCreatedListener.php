<?php

namespace App\Containers\AppSection\Notification\Listeners;

use App\Containers\AppSection\Notification\Events\NotificationCreated;
use App\Containers\AppSection\Notification\Models\UserDevice;
use App\Containers\AppSection\Notification\Services\OneSignalService;
use App\Ship\Parents\Listeners\Listener as ParentListener;
use Illuminate\Contracts\Queue\ShouldQueue;

final class NotificationCreatedListener extends ParentListener implements ShouldQueue
{
    public function __construct(private OneSignalService $oneSignalService)
    {
    }


    public function __invoke(NotificationCreated $event): void
    {
        $notification = $event->notification;
        $isBroadcast = $notification->data['broadcast'] ?? false;

        if ($isBroadcast) {
            $this->oneSignalService->sendToAll(
                $notification->title,
                $notification->message,
                $notification->data ?? []
            );
            return;
        }

        $userIds = $notification->users()->pluck('user_id')->toArray();

        if (empty($userIds)) {
            return;
        }

        $playerIds = UserDevice::whereIn('user_id', $userIds)
            ->pluck('player_id')
            ->toArray();

        if (empty($playerIds)) {
            return;
        }

        $imageUrl = $notification->data['image_url'] ?? null;
        $this->oneSignalService->sendToDevice(
            $playerIds,
            $notification->title,
            $notification->message,
            $notification->data ?? [],
            $imageUrl
        );
    }
}

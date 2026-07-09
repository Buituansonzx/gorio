<?php

namespace App\Containers\AppSection\Notification\Services;

use App\Containers\AppSection\Notification\Models\UserDevice;

class UserDeviceService
{
    public function storeDeviceUser(string $player_id, $user_id): void
    {
        UserDevice::updateOrCreate(
            [
                'player_id' => $player_id
            ],
            [
                'user_id' => $user_id,
            ]
        );
    }
}

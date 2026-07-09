<?php

namespace App\Containers\AppSection\Notification\Tasks;

use App\Containers\AppSection\Notification\Models\Notification;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\Auth;

class GetUnreadNotificationCountTask extends ParentTask
{
    public function run(): int
    {
        $userId = Auth::id();
        return Notification::whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId)
                ->where('is_read', false);
        })->count();
    }
}

<?php

namespace App\Containers\AdminSection\User\Tasks;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateBlockedUserTask extends ParentTask
{
    public function __construct()
    {
    }

    public function run($user)
    {
        $isBlocked = !$user->is_blocked;
        $user->update([
            'is_blocked' => $isBlocked,
        ]);

        if ($isBlocked) {
            $user->tokens()->update([
                'revoked' => true
            ]);
        }
    }
}

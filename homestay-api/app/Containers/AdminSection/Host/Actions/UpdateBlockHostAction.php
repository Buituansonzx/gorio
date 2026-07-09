<?php

namespace App\Containers\AdminSection\Host\Actions;

use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateBlockHostAction extends ParentAction
{
    public function __construct()
    {
    }

    public function run($hostId)
    {
        /** @var Host $host */
        $host  = Host::find($hostId);
        $user = $host->user;
        $isBlocked = !$user->is_blocked;

        $result = $user->update([
           'is_blocked' => $isBlocked
        ]);

        if ($isBlocked) {
            $user->tokens()->update([
                'revoked' => true
            ]);
        }

        $host->houses()->update(['is_active' => !$isBlocked]);
        $host->rooms()->update(['is_active' => !$isBlocked]);

        return $user->is_blocked;
    }
}

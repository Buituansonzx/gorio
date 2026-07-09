<?php

namespace App\Containers\AdminSection\Host\Actions;

use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateActiveHostAction extends ParentAction
{
    public function run($hostId)
    {
        /** @var Host $host */
        $host = Host::find($hostId);
        $host->update([
            'is_active' => !$host->is_active,
        ]);

        $host->houses()->update(['is_active' => $host->is_active]);
        $host->rooms()->update(['is_active' => $host->is_active]);

        return $host->is_active;
    }
}

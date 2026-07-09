<?php

namespace App\Containers\AdminSection\Host\Actions;

use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DetailHostAction extends ParentAction
{
    public function run($hostId)
    {
        $host  = Host::find($hostId);
        return $host;
    }
}

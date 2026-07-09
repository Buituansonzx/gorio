<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\GetRoomsByVerifiedHostsTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomsByVerifiedHostsAction extends ParentAction
{
    public function __construct(
        private readonly GetRoomsByVerifiedHostsTask $getRoomsByVerifiedHostsTask,
    ) {
    }

    public function run(): Collection
    {
        return $this->getRoomsByVerifiedHostsTask->run();
    }
}

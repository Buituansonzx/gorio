<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\GetRoomsByHostIdTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomsByHostIdAction extends ParentAction
{
    public function __construct(
        private readonly GetRoomsByHostIdTask $getRoomsByHostIdTask,
    ) {
    }

    public function run(int $hostId): Collection
    {
        return $this->getRoomsByHostIdTask->run($hostId);
    }
}

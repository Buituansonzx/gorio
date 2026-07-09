<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\GetRoomAttributesTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomAttributesAction extends ParentAction
{
    public function __construct(
        private readonly GetRoomAttributesTask $getRoomAttributesTask,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->getRoomAttributesTask->run($roomId);
    }
}

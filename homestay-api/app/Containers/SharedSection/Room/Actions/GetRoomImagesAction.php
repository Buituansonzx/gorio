<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\GetRoomImagesTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomImagesAction extends ParentAction
{
    public function __construct(
        private readonly GetRoomImagesTask $getRoomImagesTask,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->getRoomImagesTask->run($roomId);
    }
}

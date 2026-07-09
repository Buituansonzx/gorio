<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\ListRoomTypesTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListRoomTypesAction extends ParentAction
{
    public function __construct(
        private readonly ListRoomTypesTask $listRoomTypesTask,
    ) {
    }

    public function run(): Collection
    {
        return $this->listRoomTypesTask->run();
    }
}

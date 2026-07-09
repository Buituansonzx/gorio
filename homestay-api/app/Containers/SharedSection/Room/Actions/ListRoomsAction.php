<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Containers\SharedSection\Room\Tasks\ListRoomsTask;
use App\Ship\Parents\Actions\Action as ParentAction;

class ListRoomsAction extends ParentAction
{
    public function __construct(
        private readonly ListRoomsTask $listRoomsTask,
    ) {
    }

    public function run(): LengthAwarePaginator
    {
        return $this->listRoomsTask->run();
    }
}

<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\ListSurroundingFacilitiesTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListSurroundingFacilitiesAction extends ParentAction
{
    public function __construct(
        private readonly ListSurroundingFacilitiesTask $listSurroundingFacilitiesTask,
    ) {
    }

    public function run(): Collection
    {
        return $this->listSurroundingFacilitiesTask->run();
    }
}

<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\ListAttributesTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListAttributesAction extends ParentAction
{
    public function __construct(
        private readonly ListAttributesTask $listAttributesTask,
    ) {
    }

    public function run(): Collection
    {
        return $this->listAttributesTask->run();
    }
}

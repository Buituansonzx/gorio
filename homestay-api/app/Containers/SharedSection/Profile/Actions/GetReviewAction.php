<?php

namespace App\Containers\SharedSection\Profile\Actions;

use App\Containers\SharedSection\Profile\Tasks\GetReviewTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetReviewAction extends ParentAction
{
    public function __construct(private readonly GetReviewTask $task)
    {
    }

    public function run(string $hostId)
    {
        return $this->task->run($hostId);
    }
}

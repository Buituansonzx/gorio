<?php

namespace App\Containers\SharedSection\Order\Actions;

use App\Containers\SharedSection\Order\Tasks\CreateReviewTask;
use App\Containers\SharedSection\Order\UI\API\Requests\CreateReviewRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateReviewAction extends ParentAction
{
    public function __construct(private readonly CreateReviewTask $createReviewTask)
    {
    }

    public function run(CreateReviewRequest $request)
    {
        return $this->createReviewTask->run($request->validated());
    }
}

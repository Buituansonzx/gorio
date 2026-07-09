<?php

namespace App\Containers\SharedSection\Order\Actions;

use App\Containers\SharedSection\Order\Tasks\ConfirmPaymentTask;
use App\Containers\SharedSection\Order\UI\API\Requests\ConfirmPaymentRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ConfirmPaymentAction extends ParentAction
{
    public function __construct(private readonly ConfirmPaymentTask $task)
    {
    }

    public function run(ConfirmPaymentRequest $request)
    {
        return $this->task->run($request->validated());
    }
}

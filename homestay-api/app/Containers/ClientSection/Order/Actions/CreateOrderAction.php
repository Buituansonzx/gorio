<?php

namespace App\Containers\ClientSection\Order\Actions;

use App\Containers\ClientSection\Order\Tasks\CreateOrderTask;
use App\Containers\ClientSection\Order\UI\API\Requests\CreateOrderRequest;
use App\Containers\SharedSection\Order\Tasks\ValidateOrderTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateOrderAction extends ParentAction
{

    public function __construct(
        private readonly CreateOrderTask $createOrderTask,
        private  readonly ValidateOrderTask $validateOrderTask
    ) {
    }

    public function run(CreateOrderRequest $request)
    {
        $data = $this->validateOrderTask->run($request->validated());
        return $this->createOrderTask->run($data);
    }
}

<?php

namespace App\Containers\ClientSection\Order\Actions;

use App\Containers\ClientSection\Order\Tasks\UpdateOrderTask;
use App\Containers\ClientSection\Order\UI\API\Requests\UpdateOrderRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateOrderAction extends ParentAction
{
    public function __construct(private readonly UpdateOrderTask $updateOrderTask){

    }
    public function run(UpdateOrderRequest $request)
    {
        $data = $request->all();
        return $this->updateOrderTask->run($data, $request->id);
    }
}

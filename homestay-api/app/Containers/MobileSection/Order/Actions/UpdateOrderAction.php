<?php

namespace App\Containers\MobileSection\Order\Actions;

use App\Containers\MobileSection\Order\Tasks\UpdateOrderTask;
use App\Containers\MobileSection\Order\UI\API\Requests\UpdateOrderRequest;
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

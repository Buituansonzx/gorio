<?php

namespace App\Containers\ClientSection\Order\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\ClientSection\Order\Actions\UpdateOrderAction;
use App\Containers\ClientSection\Order\UI\API\Requests\UpdateOrderRequest;
use App\Containers\ClientSection\Order\UI\API\Transformers\UpdateOrderTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class UpdateOrderController extends ApiController
{

    public function __invoke(UpdateOrderRequest $request, UpdateOrderAction $action)
    {

        $order = $action->run($request);
        if (!empty($order['status'] == 'error')) {
            return $order;
        }
        return Response::create($order, UpdateOrderTransformer::class);
    }
}

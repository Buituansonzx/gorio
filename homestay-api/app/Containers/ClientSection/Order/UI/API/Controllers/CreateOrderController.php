<?php

namespace App\Containers\ClientSection\Order\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\ClientSection\Order\Actions\CreateOrderAction;
use App\Containers\ClientSection\Order\UI\API\Requests\CreateOrderRequest;
use App\Containers\ClientSection\Order\UI\API\Transformers\CreateOrderTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class CreateOrderController extends ApiController
{

    public function __invoke(CreateOrderRequest $request, CreateOrderAction $action)
    {
        try {
            $data = $action->run($request);
            return response()->json([
                'order'   => $data['order'],
                'payment' => $data['payment'],
                'bank_info' => $data['bank_info'],
                'viet_qr'  => $data['viet_qr'],
            ]);
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}

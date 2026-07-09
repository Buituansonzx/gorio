<?php

namespace App\Containers\MobileSection\Order\UI\API\Controllers;

use App\Containers\MobileSection\Order\Actions\CreateOrderAction;
use App\Containers\MobileSection\Order\UI\API\Requests\CreateOrderRequest;
use App\Ship\Parents\Controllers\ApiController;

final class CreateOrderController extends ApiController
{
    public function __invoke(CreateOrderRequest $request, CreateOrderAction $action)
    {
        $data = $action->run($request);
        return response()->json([
            'order'   => $data['order'],
            'payment' => $data['payment'],
            'bank_info' => $data['bank_info'],
            'viet_qr'  => $data['viet_qr'],
        ]);
    }
}

<?php

namespace App\Containers\SharedSection\Order\UI\API\Controllers;

use App\Containers\SharedSection\Order\Actions\ConfirmPaymentAction;
use App\Containers\SharedSection\Order\UI\API\Requests\ConfirmPaymentRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ConfirmPaymentController extends ApiController
{
    public function __invoke(ConfirmPaymentRequest $request, ConfirmPaymentAction $confirmPaymentAction)
    {
        $data = $confirmPaymentAction->run($request);
        return response()->json($data);
    }
}

<?php

namespace App\Containers\MobileSection\Order\UI\API\Controllers;

use App\Containers\MobileSection\Order\Actions\GetStatusPaymentAction;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class GetStatusPaymentController extends ApiController
{
    public function __invoke(Request $request, GetStatusPaymentAction $action)
    {
        $orderId = $request->id;
        $result = $action->run($orderId);
        return response()->json([
            'status_payment' => $result,
        ], 200);
    }

}

<?php

namespace App\Containers\SharedSection\Order\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;

final class FindTripByIdAction extends ParentAction
{
    public function run(Request $request)
    {
        $tripId = $request->id;
        $trip = Order::findorFail($tripId);
        return $trip;
    }
}

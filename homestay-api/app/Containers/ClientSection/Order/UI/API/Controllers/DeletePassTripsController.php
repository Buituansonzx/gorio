<?php

namespace App\Containers\ClientSection\Order\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\ClientSection\Order\Actions\DeletePassTripsAction;
use App\Containers\ClientSection\Order\UI\API\Requests\DeletePassTripsRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

final class DeletePassTripsController extends ApiController
{
    public function delete(DeletePassTripsRequest $request, DeletePassTripsAction $action)
    {
        $action->run($request);
        return Response::create()->noContent();
    }
}

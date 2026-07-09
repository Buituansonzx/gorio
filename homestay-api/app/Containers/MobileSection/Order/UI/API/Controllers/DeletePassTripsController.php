<?php

namespace App\Containers\MobileSection\Order\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\MobileSection\Order\Actions\DeletePassTripsAction;
use App\Containers\MobileSection\Order\UI\API\Requests\DeletePassTripsRequest;
use App\Ship\Parents\Controllers\ApiController;

final class DeletePassTripsController extends ApiController
{
    public function delete(DeletePassTripsRequest $request, DeletePassTripsAction $action)
    {
        $action->run($request);
        return Response::create()->noContent();
    }
}

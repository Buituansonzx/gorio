<?php

namespace App\Containers\SharedSection\Order\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\ClientSection\Profile\UI\API\Transformers\FindTripByIDTransformer;
use App\Containers\SharedSection\Order\Actions\FindTripByIdAction;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class FindTripByIdController extends ApiController
{
    public function __invoke(Request $request, FindTripByIdAction $action)
    {
        $trip =  $action->run($request);
        return Response::create($trip, FindTripByIDTransformer::class);
    }
}

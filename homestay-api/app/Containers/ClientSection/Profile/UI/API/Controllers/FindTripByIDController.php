<?php

namespace App\Containers\ClientSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\ClientSection\Profile\Actions\FindTripByIDAction;
use App\Containers\ClientSection\Profile\UI\API\Transformers\FindTripByIDTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class FindTripByIDController extends ApiController
{
    public function __invoke(Request $request, FindTripByIDAction $action)
    {
        $trip =  $action->run($request);
        return Response::create($trip, FindTripByIDTransformer::class);
    }
}

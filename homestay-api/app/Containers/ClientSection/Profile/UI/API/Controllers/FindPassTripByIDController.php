<?php

namespace App\Containers\ClientSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\ClientSection\Profile\Actions\FindPassTripByIDAction;
use App\Containers\ClientSection\Profile\UI\API\Transformers\FindPassTripByIDTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class FindPassTripByIDController extends ApiController
{
    public function __invoke(Request $request, FindPassTripByIDAction $action)
    {
        $passTrip =  $action->run($request);
        return Response::create($passTrip, FindPassTripByIDTransformer::class);
    }
}

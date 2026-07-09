<?php

namespace App\Containers\ClientSection\Profile\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\ClientSection\Profile\Actions\GetPassTripsAction;
use App\Containers\ClientSection\Profile\UI\API\Requests\GetPassTripsRequest;
use App\Containers\ClientSection\Profile\UI\API\Transformers\GetPassTripsTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

final class GetPassTripsController extends ApiController
{

    public function __invoke(GetPassTripsRequest $request,GetPassTripsAction $action)
    {
        $userId = Auth::id();
        $passTrips = $action->run($userId);

        return Response::create($passTrips, GetPassTripsTransformer::class);
    }
}

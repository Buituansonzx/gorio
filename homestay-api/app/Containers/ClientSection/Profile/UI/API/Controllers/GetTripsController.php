<?php

namespace App\Containers\ClientSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\ClientSection\Profile\Actions\GetTripsAction;
use App\Containers\ClientSection\Profile\UI\API\Requests\GetTripsRequest;
use App\Containers\ClientSection\Profile\UI\API\Transformers\GetTripsTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

final class GetTripsController extends ApiController
{

    public function __invoke(GetTripsRequest $request, GetTripsAction $action)
    {
        $userId = Auth::id();
        $trips = $action->run($userId);

        return Response::create($trips, GetTripsTransformer::class);
    }

}

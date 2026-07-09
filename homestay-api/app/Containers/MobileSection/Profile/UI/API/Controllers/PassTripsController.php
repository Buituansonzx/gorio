<?php

namespace App\Containers\MobileSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\MobileSection\Profile\Actions\PassTripsAction;
use App\Containers\MobileSection\Profile\UI\API\Requests\PassTripsRequest;
use App\Containers\MobileSection\Profile\UI\API\Transformers\PassTripsTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

final class PassTripsController extends ApiController
{
    public function __invoke(PassTripsRequest $request,PassTripsAction $action)
    {
        $userId = Auth::id();
        $passTrips = $action->run($userId);

        return Response::create($passTrips, PassTripsTransformer::class);
    }

}

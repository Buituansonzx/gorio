<?php

namespace App\Containers\MobileSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\MobileSection\Profile\Actions\TripsAction;
use App\Containers\MobileSection\Profile\UI\API\Requests\TripsRequest;
use App\Containers\MobileSection\Profile\UI\API\Transformers\TripsV2Transformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

final class TripsV2Controller extends ApiController
{
    public function __invoke(TripsRequest $request, TripsAction $action)
    {
        $userId = Auth::id();
        $trips = $action->run($userId);

        return Response::create($trips, TripsV2Transformer::class);
    }
}

<?php

namespace App\Containers\MobileSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\MobileSection\Profile\Actions\FindTripByIDAction;
use App\Containers\MobileSection\Profile\UI\API\Transformers\FindTripByIdV2Transformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class FindTripByIdV2Controller extends ApiController
{
    public function __invoke(Request $request, FindTripByIDAction $action)
    {
        $trip =  $action->run($request);
        return Response::create($trip, FindTripByIdV2Transformer::class);
    }
}

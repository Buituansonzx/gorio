<?php

namespace App\Containers\MobileSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\MobileSection\Profile\Actions\FindPassTripByIDAction;
use App\Containers\MobileSection\Profile\UI\API\Transformers\FindPassTripByIdV2Transformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class FindPassTripByIdVersionController extends ApiController
{
    public function findPassTripByIdV2(Request $request, FindPassTripByIDAction $action)
    {
        $data = $action->run($request);
        return Response::create($data, FindPassTripByIdV2Transformer::class);
    }
}

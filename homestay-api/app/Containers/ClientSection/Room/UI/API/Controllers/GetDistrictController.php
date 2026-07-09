<?php

namespace App\Containers\ClientSection\Room\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\ClientSection\Room\Actions\GetDistrictAction;
use App\Containers\ClientSection\Room\UI\API\Requests\GetDistrictRequest;
use App\Containers\ClientSection\Room\UI\API\Transformers\DistrictTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class GetDistrictController extends ApiController
{

    public function __invoke(GetDistrictRequest $request, GetDistrictAction $getDistrictAction)
    {
        $provinceId = $request->id;
        $districts = $getDistrictAction->run($provinceId);
        return Response::create(
            $districts, DistrictTransformer::class
        );
    }

}

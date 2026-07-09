<?php

namespace App\Containers\ClientSection\Room\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\ClientSection\Room\Actions\GetProvinceAction;
use App\Containers\ClientSection\Room\UI\API\Requests\GetProvinceRequest;
use App\Containers\ClientSection\Room\UI\API\Transformers\GetProvinceTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class GetProvinceController extends ApiController
{

    public function __invoke(GetProvinceRequest $request, GetProvinceAction $getProvinceAction)
    {
        $provinces = $getProvinceAction->run($request);
        return Response::create(
            $provinces, GetProvinceTransformer::class
        );
    }
}

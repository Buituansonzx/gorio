<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\SharedSection\Room\Actions\GetAmenitiesAction;
use App\Containers\SharedSection\Room\UI\API\Transformers\GetAmenitiesTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class GetAmenitiesController extends ApiController
{

    public function __invoke(GetAmenitiesAction $action)
    {
        $groups = $action->run();

        return response()->json([
            'status' => 'success',
            'data' => $groups
        ]);
    }
}

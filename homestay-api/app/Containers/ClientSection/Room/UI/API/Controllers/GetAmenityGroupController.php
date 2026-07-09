<?php

namespace App\Containers\ClientSection\Room\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\ClientSection\Room\Actions\GetAmenityGroupAction;
use App\Containers\ClientSection\Room\UI\API\Requests\GetAmenityGroupRequest;
use App\Containers\ClientSection\Room\UI\API\Transformers\GetAmenityGroupTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class GetAmenityGroupController extends ApiController
{
    public function __invoke(GetAmenityGroupRequest $request, GetAmenityGroupAction $action)
    {
        $groups = $action->run($request);
        return Response::create($groups, GetAmenityGroupTransformer::class );
    }
}

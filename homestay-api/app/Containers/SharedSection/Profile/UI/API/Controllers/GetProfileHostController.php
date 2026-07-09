<?php

namespace App\Containers\SharedSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\SharedSection\Profile\Actions\GetProfileHostAction;
use App\Containers\SharedSection\Profile\UI\API\Requests\GetProfileHostRequest;
use App\Containers\SharedSection\Profile\UI\API\Transformers\GetProfileHostTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class GetProfileHostController extends ApiController
{
    public function __invoke(GetProfileHostAction $action, GetProfileHostRequest $request)
    {
        $hostId = $request->id;
        $profile = $action->run($hostId);

        return Response::create($profile, GetProfileHostTransformer::class);
    }
}

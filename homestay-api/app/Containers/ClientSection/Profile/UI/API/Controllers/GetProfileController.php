<?php

namespace App\Containers\ClientSection\Profile\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\ClientSection\Profile\Actions\GetProfileAction;
use App\Containers\ClientSection\Profile\UI\API\Requests\GetProfileRequest;
use App\Containers\ClientSection\Profile\UI\API\Transformers\GetProfileTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

final class GetProfileController extends ApiController
{
    public function __invoke(GetProfileRequest $request, GetProfileAction $action)
    {
        $userId = Auth::id();
        $profile = $action->run($userId);
        return Response::create($profile, GetProfileTransformer::class);
    }

}

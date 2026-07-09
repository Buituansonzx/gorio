<?php

namespace App\Containers\MobileSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\MobileSection\Profile\Actions\ProfileAction;
use App\Containers\MobileSection\Profile\UI\API\Requests\ProfileRequest;
use App\Containers\MobileSection\Profile\UI\API\Transformers\ProfileTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

final class ProfileController extends ApiController
{
    public function __invoke(ProfileRequest $request, ProfileAction $action)
    {
        $userId = Auth::id();
        $profile = $action->run($userId);
        return Response::create($profile, ProfileTransformer::class);
    }
}

<?php

namespace App\Containers\AdminSection\AppVersion\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AdminSection\AppVersion\Actions\CreateAppVersionAction;
use App\Containers\AdminSection\AppVersion\Actions\DeleteAppVersionAction;
use App\Containers\AdminSection\AppVersion\Actions\GetAllAppVersionsAction;
use App\Containers\AdminSection\AppVersion\Actions\UpdateAppVersionAction;
use App\Containers\AdminSection\AppVersion\UI\API\Requests\CreateAppVersionRequest;
use App\Containers\AdminSection\AppVersion\UI\API\Requests\DeleteAppVersionRequest;
use App\Containers\AdminSection\AppVersion\UI\API\Requests\GetAllAppVersionsRequest;
use App\Containers\AdminSection\AppVersion\UI\API\Requests\UpdateAppVersionRequest;
use App\Containers\AdminSection\AppVersion\UI\API\Transformers\AppVersionTransformer;
use App\Ship\Parents\Controllers\ApiController;

class AppVersionController extends ApiController
{
    public function getAllAppVersions(GetAllAppVersionsRequest $request, GetAllAppVersionsAction $action)
    {
        $appVersions = $action->run($request);
        return Response::create($appVersions, AppVersionTransformer::class);
    }

    public function createAppVersion(CreateAppVersionRequest $request, CreateAppVersionAction $action)
    {
        $appVersion = $action->run($request);
        return Response::create($appVersion, AppVersionTransformer::class);
    }

    public function updateAppVersion(UpdateAppVersionRequest $request, UpdateAppVersionAction $action)
    {
        $appVersion = $action->run($request);
        return Response::create($appVersion, AppVersionTransformer::class);
    }

    public function deleteAppVersion(DeleteAppVersionRequest $request, DeleteAppVersionAction $action)
    {
        $action->run($request);
        return response()->noContent();
    }
}

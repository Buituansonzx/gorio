<?php

namespace App\Containers\MobileSection\AppVersion\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\MobileSection\AppVersion\Actions\CheckAppVersionAction;
use App\Containers\MobileSection\AppVersion\UI\API\Requests\CheckAppVersionRequest;
use App\Containers\MobileSection\AppVersion\UI\API\Transformers\AppVersionTransformer;
use App\Ship\Parents\Controllers\ApiController;

class CheckAppVersionController extends ApiController
{
    public function checkVersion(CheckAppVersionRequest $request, CheckAppVersionAction $action)
    {
        $appVersion = $action->run($request);
        
        if (!$appVersion) {
            return response()->json([
                'data' => [
                    'has_update' => false
                ]
            ]);
        }

        return Response::create($appVersion, AppVersionTransformer::class);
    }
}

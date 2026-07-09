<?php

namespace App\Containers\AdminSection\AppVersion\Actions;

use App\Containers\AdminSection\AppVersion\UI\API\Requests\GetAllAppVersionsRequest;
use App\Containers\SharedSection\AppVersion\Models\AppVersion;
use App\Ship\Parents\Actions\Action as ParentAction;

class GetAllAppVersionsAction extends ParentAction
{
    public function run(GetAllAppVersionsRequest $request)
    {
        return AppVersion::orderBy('created_at', 'desc')->paginate();
    }
}

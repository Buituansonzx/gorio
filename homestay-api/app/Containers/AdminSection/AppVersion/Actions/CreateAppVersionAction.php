<?php

namespace App\Containers\AdminSection\AppVersion\Actions;

use App\Containers\AdminSection\AppVersion\UI\API\Requests\CreateAppVersionRequest;
use App\Containers\SharedSection\AppVersion\Models\AppVersion;
use App\Ship\Parents\Actions\Action as ParentAction;

class CreateAppVersionAction extends ParentAction
{
    public function run(CreateAppVersionRequest $request)
    {
        return AppVersion::create($request->validated());
    }
}

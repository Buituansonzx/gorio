<?php

namespace App\Containers\AdminSection\AppVersion\Actions;

use App\Containers\AdminSection\AppVersion\UI\API\Requests\UpdateAppVersionRequest;
use App\Containers\SharedSection\AppVersion\Models\AppVersion;
use App\Ship\Parents\Actions\Action as ParentAction;

class UpdateAppVersionAction extends ParentAction
{
    public function run(UpdateAppVersionRequest $request)
    {
        $appVersion = AppVersion::findOrFail($request->id);
        $appVersion->update($request->validated());
        return $appVersion;
    }
}

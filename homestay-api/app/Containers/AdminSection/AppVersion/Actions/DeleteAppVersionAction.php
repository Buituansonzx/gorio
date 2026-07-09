<?php

namespace App\Containers\AdminSection\AppVersion\Actions;

use App\Containers\AdminSection\AppVersion\UI\API\Requests\DeleteAppVersionRequest;
use App\Containers\SharedSection\AppVersion\Models\AppVersion;
use App\Ship\Parents\Actions\Action as ParentAction;

class DeleteAppVersionAction extends ParentAction
{
    public function run(DeleteAppVersionRequest $request)
    {
        return AppVersion::findOrFail($request->id)->delete();
    }
}

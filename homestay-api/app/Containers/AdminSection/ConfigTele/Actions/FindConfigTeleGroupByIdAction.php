<?php

namespace App\Containers\AdminSection\ConfigTele\Actions;

use App\Containers\AdminSection\ConfigTele\Models\ConfigTeleGroup;
use App\Containers\AdminSection\ConfigTele\Tasks\FindConfigTeleGroupByIdTask;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\FindConfigTeleGroupByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class FindConfigTeleGroupByIdAction extends ParentAction
{
    public function run(FindConfigTeleGroupByIdRequest $request): ConfigTeleGroup
    {
        return app(FindConfigTeleGroupByIdTask::class)->run($request->id);
    }
}

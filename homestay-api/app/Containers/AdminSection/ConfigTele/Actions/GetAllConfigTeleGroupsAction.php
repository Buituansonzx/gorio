<?php

namespace App\Containers\AdminSection\ConfigTele\Actions;

use App\Containers\AdminSection\ConfigTele\Tasks\GetAllConfigTeleGroupsTask;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\GetAllConfigTeleGroupsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class GetAllConfigTeleGroupsAction extends ParentAction
{
    public function run(GetAllConfigTeleGroupsRequest $request)
    {
        return app(GetAllConfigTeleGroupsTask::class)->run();
    }
}

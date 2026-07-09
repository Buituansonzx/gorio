<?php

namespace App\Containers\AdminSection\ConfigTele\Actions;

use App\Containers\AdminSection\ConfigTele\Models\ConfigTeleGroup;
use App\Containers\AdminSection\ConfigTele\Tasks\CreateConfigTeleGroupTask;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\CreateConfigTeleGroupRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class CreateConfigTeleGroupAction extends ParentAction
{
    public function run(CreateConfigTeleGroupRequest $request): ConfigTeleGroup
    {
        $data = $request->validated();

        return app(CreateConfigTeleGroupTask::class)->run($data);
    }
}

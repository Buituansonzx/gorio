<?php

namespace App\Containers\AdminSection\ConfigTele\Actions;

use App\Containers\AdminSection\ConfigTele\Models\ConfigTeleGroup;
use App\Containers\AdminSection\ConfigTele\Tasks\UpdateConfigTeleGroupTask;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\UpdateConfigTeleGroupRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class UpdateConfigTeleGroupAction extends ParentAction
{
    public function run(UpdateConfigTeleGroupRequest $request): ConfigTeleGroup
    {
        $data = $request->validated();

        return app(UpdateConfigTeleGroupTask::class)->run($request->id, $data);
    }
}

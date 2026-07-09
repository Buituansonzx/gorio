<?php

namespace App\Containers\AdminSection\ConfigTele\Actions;

use App\Containers\AdminSection\ConfigTele\Tasks\DeleteConfigTeleGroupTask;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\DeleteConfigTeleGroupRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class DeleteConfigTeleGroupAction extends ParentAction
{
    public function run(DeleteConfigTeleGroupRequest $request): void
    {
        app(DeleteConfigTeleGroupTask::class)->run($request->id);
    }
}

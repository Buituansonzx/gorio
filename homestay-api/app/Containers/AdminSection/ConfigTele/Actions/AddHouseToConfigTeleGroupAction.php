<?php

namespace App\Containers\AdminSection\ConfigTele\Actions;

use App\Containers\AdminSection\ConfigTele\Models\ConfigTeleGroup;
use App\Containers\AdminSection\ConfigTele\Tasks\AddHouseToConfigTeleGroupTask;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\AddHouseToConfigTeleGroupRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class AddHouseToConfigTeleGroupAction extends ParentAction
{
    public function run(AddHouseToConfigTeleGroupRequest $request): ConfigTeleGroup
    {
        $configTeleGroupId = $request->id;
        $houseIds = $request->house_ids;

        return app(AddHouseToConfigTeleGroupTask::class)->run($configTeleGroupId, $houseIds);
    }
}

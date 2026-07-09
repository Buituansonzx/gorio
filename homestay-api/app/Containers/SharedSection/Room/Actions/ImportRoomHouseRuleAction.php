<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportRoomHouseRuleTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportHouseRuleRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportRoomHouseRuleAction extends ParentAction
{
    public function run(ImportHouseRuleRequest $request)
    {
        return app(ImportRoomHouseRuleTask::class)->run(
            $request->file('files')
        );
    }
}

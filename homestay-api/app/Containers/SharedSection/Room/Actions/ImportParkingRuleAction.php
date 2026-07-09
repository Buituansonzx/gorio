<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportParkingRuleTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportParkingRuleRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportParkingRuleAction extends ParentAction
{
    public function run(ImportParkingRuleRequest $request)
    {
        return app(ImportParkingRuleTask::class)->run($request->file('files'));
    }
}

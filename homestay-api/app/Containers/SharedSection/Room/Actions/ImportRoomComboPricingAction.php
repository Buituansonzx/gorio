<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportRoomComboPricingTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomComboPricingRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportRoomComboPricingAction extends ParentAction
{
    public function run(ImportRoomComboPricingRequest $request)
    {
        return app(ImportRoomComboPricingTask::class)->run($request->file('files'));
    }
}

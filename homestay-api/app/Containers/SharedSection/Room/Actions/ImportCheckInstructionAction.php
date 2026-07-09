<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportCheckInstructionTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportCheckInstructionRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportCheckInstructionAction extends ParentAction
{
    public function run(ImportCheckInstructionRequest $request)
    {
        return app(ImportCheckInstructionTask::class)->run($request->file('files'));
    }
}

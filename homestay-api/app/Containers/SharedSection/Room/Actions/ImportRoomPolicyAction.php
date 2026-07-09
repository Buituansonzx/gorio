<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportRoomPolicyTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomPolicyRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportRoomPolicyAction extends ParentAction
{
    public function run(ImportRoomPolicyRequest $request)
    {
        return app(ImportRoomPolicyTask::class)->run(
            $request->file('files')
        );
    }
}

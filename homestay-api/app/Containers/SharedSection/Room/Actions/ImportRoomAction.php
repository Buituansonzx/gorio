<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportRoomTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportRoomAction extends ParentAction
{
    public function run(ImportRoomRequest $request)
    {
        return app(ImportRoomTask::class)->run($request->file('files'));
    }
}

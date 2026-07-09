<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportRoomFixedCheckTimeTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomFixedCheckTimeRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportRoomFixedCheckTimeAction extends ParentAction
{
    public function run(ImportRoomFixedCheckTimeRequest $request)
    {
        return app(ImportRoomFixedCheckTimeTask::class)->run($request->file('files'));
    }
}

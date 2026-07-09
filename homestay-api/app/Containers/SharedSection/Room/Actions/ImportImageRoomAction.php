<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportImageRoomTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportImageRoomRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportImageRoomAction extends ParentAction
{

        public function run(ImportImageRoomRequest $request)
    {
        return app(ImportImageRoomTask::class)->run($request->file('files'));
    }

}

<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportHighlightAmenityTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomHighlightAmenityRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportHighlightAmenityAction extends ParentAction
{
    public function run(ImportRoomHighlightAmenityRequest $request)
    {
        return app(ImportHighlightAmenityTask::class)->run($request->file('files'));
    }
}

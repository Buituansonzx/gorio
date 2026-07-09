<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportReviewTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportReviewRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportReviewAction extends ParentAction
{
    public function run(ImportReviewRequest $request)
    {
        return app(ImportReviewTask::class)->run($request->file('files'));
    }
}

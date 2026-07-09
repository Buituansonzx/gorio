<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportRoomWeekdayPriceTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportRoomWeekdayPriceAction extends ParentAction
{
    public function run(ImportRoomWeekdayPriceRequest $request)
    {
        return app(ImportRoomWeekdayPriceTask::class)->run($request->file('files'));
    }
}

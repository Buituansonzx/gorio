<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportRoomHourlyPricingTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomHourlyPricingRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportRoomHourlyPricingAction extends ParentAction
{
    public function run(ImportRoomHourlyPricingRequest $request)
    {
        return app(ImportRoomHourlyPricingTask::class)->run($request->file('files'));
    }
}

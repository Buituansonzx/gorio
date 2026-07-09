<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportRoomDiscountPricingTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomDiscountPricingRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportRoomDiscountPricingAction extends ParentAction
{
    public function run(ImportRoomDiscountPricingRequest $request)
    {
        return app(ImportRoomDiscountPricingTask::class)
            ->run($request->file('files'));

    }
}

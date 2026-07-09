<?php

namespace App\Containers\MobileSection\Order\Actions;

use App\Containers\MobileSection\Order\Tasks\DeletePassTripsTask;
use App\Containers\MobileSection\Order\UI\API\Requests\DeletePassTripsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeletePassTripsAction extends ParentAction
{
    public function __construct(private readonly DeletePassTripsTask $deletePassTripsTask)
    {
    }

    public function run(DeletePassTripsRequest $request)
    {
        return $this->deletePassTripsTask->run($request->validated());
    }
}

<?php

namespace App\Containers\ClientSection\Order\Actions;

use App\Containers\ClientSection\Order\Tasks\DeletePassTripsTask;
use App\Containers\ClientSection\Order\UI\API\Requests\DeletePassTripsRequest;
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

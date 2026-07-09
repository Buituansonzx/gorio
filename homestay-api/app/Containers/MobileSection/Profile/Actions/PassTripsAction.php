<?php

namespace App\Containers\MobileSection\Profile\Actions;

use App\Containers\MobileSection\Profile\Tasks\PassTripsTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class PassTripsAction extends ParentAction
{
    public function __construct(private readonly PassTripsTask $getProfileTask)
    {

    }
    public function run(string $userId)
    {
        return $this->getProfileTask->run($userId);
    }
}

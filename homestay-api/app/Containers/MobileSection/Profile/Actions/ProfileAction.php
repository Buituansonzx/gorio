<?php

namespace App\Containers\MobileSection\Profile\Actions;

use App\Containers\MobileSection\Profile\Tasks\ProfileTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ProfileAction extends ParentAction
{
    public function __construct(private readonly ProfileTask $getProfileTask)
    {
    }

    public function run(string $userId)
    {
        return $this->getProfileTask->run($userId);
    }
}

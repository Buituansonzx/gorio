<?php

namespace App\Containers\ClientSection\Profile\Actions;

use App\Containers\ClientSection\Profile\Tasks\GetProfileTask;
use App\Containers\ClientSection\Profile\UI\API\Requests\GetProfileRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetProfileAction extends ParentAction
{

    public function __construct(private readonly GetProfileTask $getProfileTask)
    {
    }

    public function run(string $userId)
    {
        return $this->getProfileTask->run($userId);
    }
}

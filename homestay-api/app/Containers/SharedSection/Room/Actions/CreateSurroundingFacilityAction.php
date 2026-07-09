<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\SurroundingFacility;
use App\Containers\SharedSection\Room\Tasks\CreateSurroundingFacilityTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateSurroundingFacilityAction extends ParentAction
{
    public function __construct(
        private readonly CreateSurroundingFacilityTask $createSurroundingFacilityTask,
    ) {
    }

    public function run(array $data): SurroundingFacility
    {
        return $this->createSurroundingFacilityTask->run($data);
    }
}

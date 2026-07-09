<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomPricingPolicy;
use App\Containers\SharedSection\Room\Tasks\CreateRoomPricingPolicyTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateRoomPricingPolicyAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomPricingPolicyTask $createRoomPricingPolicyTask,
    ) {
    }

    public function run(array $data): RoomPricingPolicy
    {
        return $this->createRoomPricingPolicyTask->run($data);
    }
}

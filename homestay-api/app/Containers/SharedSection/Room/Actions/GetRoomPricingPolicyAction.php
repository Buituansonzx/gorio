<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomPricingPolicy;
use App\Containers\SharedSection\Room\Tasks\GetRoomPricingPolicyTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomPricingPolicyAction extends ParentAction
{
    public function __construct(
        private readonly GetRoomPricingPolicyTask $getRoomPricingPolicyTask,
    ) {
    }

    public function run(int $roomId): ?RoomPricingPolicy
    {
        return $this->getRoomPricingPolicyTask->run($roomId);
    }
}

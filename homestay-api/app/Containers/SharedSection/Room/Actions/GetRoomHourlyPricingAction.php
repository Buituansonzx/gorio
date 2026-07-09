<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\GetRoomHourlyPricingTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomHourlyPricingAction extends ParentAction
{
    public function __construct(
        private readonly GetRoomHourlyPricingTask $getRoomHourlyPricingTask,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->getRoomHourlyPricingTask->run($roomId);
    }
}

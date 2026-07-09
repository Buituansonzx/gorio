<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomHourlyPricing;
use App\Containers\SharedSection\Room\Tasks\CreateRoomHourlyPricingTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateRoomHourlyPricingAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomHourlyPricingTask $createRoomHourlyPricingTask,
    ) {
    }

    public function run(array $data): RoomHourlyPricing
    {
        return $this->createRoomHourlyPricingTask->run($data);
    }
}

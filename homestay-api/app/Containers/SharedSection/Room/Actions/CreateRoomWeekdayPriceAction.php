<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomWeekdayPrice;
use App\Containers\SharedSection\Room\Tasks\CreateRoomWeekdayPriceTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateRoomWeekdayPriceAction extends ParentAction
{
    public function __construct(
        private readonly CreateRoomWeekdayPriceTask $createRoomWeekdayPriceTask,
    ) {
    }

    public function run(array $data): RoomWeekdayPrice
    {
        return $this->createRoomWeekdayPriceTask->run($data);
    }
}

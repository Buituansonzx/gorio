<?php

namespace App\Containers\SharedSection\Room\Actions;

use Illuminate\Database\Eloquent\Collection;
use App\Containers\SharedSection\Room\Tasks\GetRoomWeekdayPricesTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetRoomWeekdayPricesAction extends ParentAction
{
    public function __construct(
        private readonly GetRoomWeekdayPricesTask $getRoomWeekdayPricesTask,
    ) {
    }

    public function run(int $roomId): Collection
    {
        return $this->getRoomWeekdayPricesTask->run($roomId);
    }
}

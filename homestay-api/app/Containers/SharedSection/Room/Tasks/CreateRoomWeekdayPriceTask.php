<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomWeekdayPriceRepository;
use App\Containers\SharedSection\Room\Models\RoomWeekdayPrice;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateRoomWeekdayPriceTask extends ParentTask
{
    public function __construct(
        private readonly RoomWeekdayPriceRepository $repository,
    ) {
    }

    public function run(array $data): RoomWeekdayPrice
    {
        return $this->repository->create($data);
    }
}

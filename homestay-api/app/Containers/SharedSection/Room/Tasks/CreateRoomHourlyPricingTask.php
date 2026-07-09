<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomHourlyPricingRepository;
use App\Containers\SharedSection\Room\Models\RoomHourlyPricing;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateRoomHourlyPricingTask extends ParentTask
{
    public function __construct(
        private readonly RoomHourlyPricingRepository $repository,
    ) {
    }

    public function run(array $data): RoomHourlyPricing
    {
        return $this->repository->create($data);
    }
}

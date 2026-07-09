<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Order\Data\Repositories\OrderRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetBusyTimeTask extends ParentTask
{
    public function __construct(private readonly OrderRepository $orderRepository)
    {
    }

    public function run(string $roomId)
    {
        return $this->orderRepository->getBusyTime($roomId);
    }
}

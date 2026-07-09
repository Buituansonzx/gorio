<?php

namespace App\Containers\SharedSection\Profile\Tasks;

use App\Containers\SharedSection\Room\Data\Repositories\RoomRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetListingRoomByHostIdTask extends ParentTask
{
    public function __construct(private readonly RoomRepository $roomRepository)
    {
    }

    public function run(string $hostId)
    {
        return $this->roomRepository->listingRoomByHostId( $hostId);
    }
}

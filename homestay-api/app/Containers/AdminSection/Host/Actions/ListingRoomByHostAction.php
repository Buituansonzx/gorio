<?php

namespace App\Containers\AdminSection\Host\Actions;

use App\Containers\SharedSection\Room\Data\Repositories\RoomRepository;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListingRoomByHostAction extends ParentAction
{

    public function __construct(private readonly RoomRepository $roomRepository)
    {
    }

    public function run($hostId, $pageSize)
    {
        return $this->roomRepository->getRoomsByHostId($hostId, $pageSize);
    }
}

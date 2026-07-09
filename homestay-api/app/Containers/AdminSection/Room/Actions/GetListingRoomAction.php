<?php

namespace App\Containers\AdminSection\Room\Actions;

use App\Containers\SharedSection\Room\Data\Repositories\RoomRepository;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetListingRoomAction extends ParentAction
{
    public function __construct(private readonly RoomRepository $roomRepository)
    {
    }

    public function run($request)
    {
        return $this->roomRepository->listingRoomsAdmin($request->validated());
    }
}

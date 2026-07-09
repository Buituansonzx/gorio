<?php

namespace App\Containers\MobileSection\Room\Actions;

use App\Containers\MobileSection\Room\Tasks\ListRoomTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListRoomsAction extends ParentAction
{
        public function __construct(
        private readonly ListRoomTask $listRoomsTask,
    ) {
        // Override parent constructor
    }

        /**
         * List available rooms for clients with filters
         */
        public function run(array $filters = [])
    {
        return $this->listRoomsTask->run($filters);
    }

}

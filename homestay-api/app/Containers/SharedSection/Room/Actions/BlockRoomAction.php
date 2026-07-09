<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\BlockRoomTask;
use App\Containers\SharedSection\Room\UI\API\Requests\BlockRoomRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class BlockRoomAction extends ParentAction
{
    public function __construct(private readonly BlockRoomTask $blockRoomTask)
    {
    }

    public function run(BlockRoomRequest $request)
    {
        $roomId = $request->id;
        return $this->blockRoomTask->run($request->validated(),$roomId);
    }
}

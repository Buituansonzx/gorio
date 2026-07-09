<?php

namespace App\Containers\MobileSection\Room\Actions;

use App\Containers\MobileSection\Room\Tasks\ListFavoriteRoomTask;
use App\Containers\MobileSection\Room\UI\API\Requests\ListFavoriteRoomRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListFavoriteRoomAction extends ParentAction
{
    public function __construct(private readonly ListFavoriteRoomTask $task)
    {
    }

    public function run(ListFavoriteRoomRequest $request)
    {
        return $this->task->run($request->validated());
    }
}

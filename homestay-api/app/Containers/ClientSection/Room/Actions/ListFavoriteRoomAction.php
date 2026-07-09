<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Containers\ClientSection\Room\Tasks\ListFavoriteRoomTask;
use App\Containers\ClientSection\Room\UI\API\Requests\ListFavoriteRoomRequest;
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

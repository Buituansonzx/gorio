<?php

namespace App\Containers\ClientSection\Room\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\ClientSection\Room\Actions\GetRoomImageAreaGroupAction;
use App\Containers\ClientSection\Room\UI\API\Requests\GetRoomImageAreaGroupRequest;
use App\Containers\ClientSection\Room\UI\API\Transformers\RoomImageAreaGroupTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class GetRoomImageAreaGroupController extends ApiController
{
    public function __invoke(GetRoomImageAreaGroupRequest $request, GetRoomImageAreaGroupAction $action )
    {
        $groups = $action->run($request);
        return Response::create($groups, RoomImageAreaGroupTransformer::class);
    }
}

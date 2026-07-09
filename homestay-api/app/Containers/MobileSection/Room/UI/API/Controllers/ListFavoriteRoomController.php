<?php

namespace App\Containers\MobileSection\Room\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\MobileSection\Room\Actions\ListFavoriteRoomAction;
use App\Containers\MobileSection\Room\UI\API\Requests\ListFavoriteRoomRequest;
use App\Containers\MobileSection\Room\UI\API\Transformers\ListFavoriteRoomTransformer;
use App\Ship\Parents\Controllers\ApiController;

final class ListFavoriteRoomController extends ApiController
{
    public function listFavoriteRoom(ListFavoriteRoomRequest $request,ListFavoriteRoomAction $action)
    {
        $data = $action->run($request);
        return Response::create($data, ListFavoriteRoomTransformer::class);
    }
}

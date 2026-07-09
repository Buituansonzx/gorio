<?php

namespace App\Containers\SharedSection\Profile\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\ClientSection\Room\UI\API\Transformers\RoomTransformer;
use App\Containers\MobileSection\Room\UI\API\Transformers\ListFavoriteRoomTransformer;
use App\Containers\SharedSection\Profile\Actions\GetListingRoomByHostIdAction;
use App\Containers\SharedSection\Profile\UI\API\Requests\GetListingRoomByHostIdRequest;
use App\Ship\Parents\Controllers\ApiController;

final class MobileListingRoomByHostController extends ApiController
{
    public function __invoke(GetListingRoomByHostIdRequest $request, GetListingRoomByHostIdAction $action)
    {
        $hostId = $request->id;
        $rooms = $action->run($hostId);

        return Response::create($rooms, ListFavoriteRoomTransformer::class);
    }
}

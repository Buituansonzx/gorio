<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportImageRoomAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportImageRoomRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportImageRoomController extends ApiController
{
    public function __invoke(ImportImageRoomRequest $request)
    {
        app(ImportImageRoomAction::class)->run($request);
        return response()->json(['message' => 'Image import room successful']);
    }

}

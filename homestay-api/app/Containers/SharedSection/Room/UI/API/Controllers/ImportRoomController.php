<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportRoomAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportRoomController extends ApiController
{
    public function __invoke(ImportRoomRequest $request)
    {
        app(ImportRoomAction::class)->run($request);
        return response()->json(['message' => 'Import successful']);
    }
}

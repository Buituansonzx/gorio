<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportRoomWeekdayPriceAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomWeekdayPriceRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportRoomWeekdayPriceController extends ApiController
{

    public function __invoke(ImportRoomWeekdayPriceRequest $request)
    {
        app(ImportRoomWeekdayPriceAction::class)->run($request);
        return response()->json(['message' => 'Import successful']);
    }
}

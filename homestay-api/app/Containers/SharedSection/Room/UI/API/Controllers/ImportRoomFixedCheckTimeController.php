<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportRoomFixedCheckTimeAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomFixedCheckTimeRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportRoomFixedCheckTimeController extends ApiController
{
    public function __invoke(ImportRoomFixedCheckTimeRequest $request)
    {
        app(ImportRoomFixedCheckTimeAction::class)->run($request);
        return response()->json(['message' => 'Import Room Fixed Check Time Successfully.']);
    }
}

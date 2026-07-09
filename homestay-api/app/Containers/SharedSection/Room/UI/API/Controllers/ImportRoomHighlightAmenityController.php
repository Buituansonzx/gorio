<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportHighlightAmenityAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomHighlightAmenityRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportRoomHighlightAmenityController extends ApiController
{
    public function __invoke(ImportRoomHighlightAmenityRequest $request)
    {
        app(ImportHighlightAmenityAction::class)->run($request);
        return response()->json(['message' => 'Import successful']);
    }
}

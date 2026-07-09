<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportHighlightAmenityImageAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomHighlightAmenityImageRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportHighlightAmenityImageController extends ApiController
{
    public function __invoke(ImportRoomHighlightAmenityImageRequest $request)
    {
        app(ImportHighlightAmenityImageAction::class)->run($request);
        return response()->json(['message' => 'Import successful']);
    }
}

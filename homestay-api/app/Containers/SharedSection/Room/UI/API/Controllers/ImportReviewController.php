<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportReviewAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportReviewRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportReviewController extends ApiController
{

    public function __invoke(ImportReviewRequest $request)
    {
        app(ImportReviewAction::class)->run($request);
        return response()->json(['message' => 'Import successful']);
    }
}

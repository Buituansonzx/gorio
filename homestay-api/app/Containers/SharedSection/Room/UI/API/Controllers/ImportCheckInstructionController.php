<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportCheckInstructionAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportCheckInstructionRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportCheckInstructionController extends ApiController
{
    public function __invoke(ImportCheckInstructionRequest $request )
    {
        app(ImportCheckInstructionAction::class)->run($request);
        return response()->json(['message' => 'Import Check Instruction Successfully.']);
    }
}

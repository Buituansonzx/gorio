<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportParkingRuleAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportParkingRuleRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportParkingRuleController extends ApiController
{
    public function __invoke(ImportParkingRuleRequest $request)
    {
        app(ImportParkingRuleAction::class)->run($request);
        return response()->json(['message' => 'Parking rule import successfully.']);
    }
}

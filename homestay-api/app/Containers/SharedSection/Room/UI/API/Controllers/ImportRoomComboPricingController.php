<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportRoomComboPricingAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomComboPricingRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportRoomComboPricingController extends ApiController
{
    public function __invoke(ImportRoomComboPricingRequest $request)
    {
        app(ImportRoomComboPricingAction::class)->run($request);
        return response()->json(['message' => 'Import Room Combo Pricing Successfully.']);
    }
}

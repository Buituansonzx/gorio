<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportRoomHourlyPricingAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomHourlyPricingRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportHourlyPricingController extends ApiController
{
    public function __invoke(ImportRoomHourlyPricingRequest $request)
    {
        app(ImportRoomHourlyPricingAction::class)->run($request);
        return response()->json(['message' => 'Import Hourly Pricing Successfully.']);
    }
}

<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportRoomDiscountPricingAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomDiscountPricingRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportRoomDiscountPricingController extends ApiController
{
    public function __invoke(ImportRoomDiscountPricingRequest $request)
    {
        app(ImportRoomDiscountPricingAction::class)->run($request);
        return response()->json(['message' => 'Import Room Discount Pricing Successfully.']);
    }
}

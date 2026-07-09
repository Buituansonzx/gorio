<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportRoomPricingPolicyAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomPricingPolicyRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportRoomPricingPolicyController extends ApiController
{
    public function __invoke(ImportRoomPricingPolicyRequest $request)
    {
        app(ImportRoomPricingPolicyAction::class)->run($request);
        return response()->json(['message' => 'Import successful']);
    }
}

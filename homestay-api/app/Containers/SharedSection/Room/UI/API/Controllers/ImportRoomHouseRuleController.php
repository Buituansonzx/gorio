<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportRoomHouseRuleAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportHouseRuleRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportRoomHouseRuleController extends ApiController
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ImportHouseRuleRequest $request)
    {
        app(ImportRoomHouseRuleAction::class)->run($request);
        return response()->json(['message' => 'Room house rules imported successfully.'], 200);
    }
}

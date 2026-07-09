<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\ImportRoomPolicyAction;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomPolicyRequest;
use App\Ship\Parents\Controllers\ApiController;

final class ImportRoomPolicyController extends ApiController
{
    public function __invoke(ImportRoomPolicyRequest $request)
    {
        app(ImportRoomPolicyAction::class)->run($request);
        return response()->json(['message' => 'Room house rules imported successfully.'], 200);
    }
}

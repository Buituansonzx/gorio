<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Tasks\ImportRoomPricingPolicyTask;
use App\Containers\SharedSection\Room\UI\API\Requests\ImportRoomPricingPolicyRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ImportRoomPricingPolicyAction extends ParentAction
{
    public function run(ImportRoomPricingPolicyRequest $request)
    {
        return app(ImportRoomPricingPolicyTask::class)->run($request->file('files'));
    }
}

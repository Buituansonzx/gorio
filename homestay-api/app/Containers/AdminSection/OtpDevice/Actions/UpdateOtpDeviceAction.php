<?php

namespace App\Containers\AdminSection\OtpDevice\Actions;

use App\Containers\AdminSection\OtpDevice\Tasks\UpdateOtpDeviceTask;
use App\Containers\AdminSection\OtpDevice\UI\API\Requests\UpdateOtpDeviceRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class UpdateOtpDeviceAction extends ParentAction
{
    public function run(UpdateOtpDeviceRequest $request)
    {
        $data = $request->validated();

        return app(UpdateOtpDeviceTask::class)->run($request->id, $data);
    }
}

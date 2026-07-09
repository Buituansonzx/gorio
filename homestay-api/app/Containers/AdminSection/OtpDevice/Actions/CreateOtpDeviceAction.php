<?php

namespace App\Containers\AdminSection\OtpDevice\Actions;

use App\Containers\AdminSection\OtpDevice\Tasks\CreateOtpDeviceTask;
use App\Containers\AdminSection\OtpDevice\UI\API\Requests\CreateOtpDeviceRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

class CreateOtpDeviceAction extends ParentAction
{
    public function run(CreateOtpDeviceRequest $request)
    {
        $data = $request->validated();

        $data['is_active'] = false;

        return app(CreateOtpDeviceTask::class)->run($data);
    }
}

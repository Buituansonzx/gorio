<?php

namespace App\Containers\AdminSection\OtpDevice\UI\API\Controllers;

use App\Containers\SharedSection\OtpDevice\Data\Repositories\OtpDeviceRepository;
use App\Containers\SharedSection\OtpDevice\Models\OtpDevice;
use App\Containers\AdminSection\OtpDevice\Actions\CreateOtpDeviceAction;
use App\Containers\AdminSection\OtpDevice\Actions\UpdateOtpDeviceAction;
use App\Containers\AdminSection\OtpDevice\UI\API\Requests\CreateOtpDeviceRequest;
use App\Containers\AdminSection\OtpDevice\UI\API\Requests\UpdateOtpDeviceRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class OtpDeviceController extends ApiController
{

    public function listing()
    {
        $devices = app(OtpDeviceRepository::class)->all();
        return response()->json([
            'success' => true,
            'data' => $devices
        ]);
    }

    public function toggleActive(Request $request)
    {
        $deviceId = $request->id;
        $deviceOtp = OtpDevice::findOrFail($deviceId);
        $deviceOtp->update([
            'is_active' => !$deviceOtp->is_active
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'data' => $deviceOtp->fresh()
        ]);
    }

    public function create(CreateOtpDeviceRequest $request, CreateOtpDeviceAction $action)
    {
        $device = $action->run($request);

        return response()->json([
            'success' => true,
            'message' => 'Tạo mới thành công',
            'data' => $device
        ]);
    }

    public function update(UpdateOtpDeviceRequest $request, UpdateOtpDeviceAction $action)
    {
        $device = $action->run($request);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $device
        ]);
    }

}

<?php

namespace App\Containers\AdminSection\Setting\UI\API\Controllers;

use App\Containers\AdminSection\Setting\Actions\ToggleAuthSupportAction;
use App\Containers\AdminSection\Setting\UI\API\Requests\GetAuthSupportStatusRequest;
use App\Containers\AdminSection\Setting\UI\API\Requests\ToggleAuthSupportRequest;
use App\Containers\SharedSection\Room\Models\Setting;
use App\Ship\Parents\Controllers\ApiController;

class SettingController extends ApiController
{
    public function toggleAuthSupport(ToggleAuthSupportRequest $request, ToggleAuthSupportAction $action)
    {
        $setting = $action->run($request);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái hệ thống hỗ trợ thành công!',
            'data' => [
                'key' => $setting->key,
                'is_enabled' => $setting->value === '1'
            ]
        ]);
    }

    public function getAuthSupportStatus(GetAuthSupportStatusRequest $request)
    {
        $setting = Setting::where('key', 'check_phone_support_status')->first();
        
        return response()->json([
            'success' => true,
            'data' => [
                'key' => 'check_phone_support_status',
                'is_enabled' => $setting ? ($setting->value === '1') : false
            ]
        ]);
    }
}

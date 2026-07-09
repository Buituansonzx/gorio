<?php

namespace App\Containers\AppSection\Notification\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AppSection\Notification\Models\UserDevice;
use App\Containers\AppSection\Notification\Services\UserDeviceService;
use App\Containers\AppSection\Notification\UI\API\Requests\RegisterDeviceRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class UserDeviceController extends ApiController
{
    public function registerDevice(RegisterDeviceRequest $request, UserDeviceService $service): JsonResponse
    {
        $userId = Auth::id();
        $playerId = $request->input('player_id');
        $service->storeDeviceUser(
            $playerId,
            $userId
        );
        return response()->json([
            'message' => 'Device registered successfully',
            'player_id' => $playerId,
            'user_id' => $userId
        ], 201);
    }

    public function unregisterDevice(Request $request,UserDeviceService $service): JsonResponse
    {
        $playerId = $request->id;
        $service->storeDeviceUser(
            $playerId,null
        );
        return response()->json([
            'message' => 'Device unregistered successfully'
        ], 204);
    }
}

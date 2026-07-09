<?php

namespace App\Containers\AppSection\Auth\UI\API\Controllers;

use App\Containers\AppSection\Auth\UI\API\Requests\RefreshTokenRequest;
use App\Containers\AppSection\Auth\Actions\RefreshTokenAction;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class RefreshTokenController extends ApiController
{
    /**
     * Refresh access token using refresh token
     * 
     * @param RefreshTokenRequest $request
     * @param RefreshTokenAction $action
     * @return JsonResponse
     */
    public function refreshToken(RefreshTokenRequest $request, RefreshTokenAction $action): JsonResponse
    {
        $data = $request->sanitize([
            'refresh_token',
        ]);

        $result = $action->run($data);

        return response()->json([
            'message' => 'Token refreshed successfully',
            'data' => $result,
        ]);
    }
}
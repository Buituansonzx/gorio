<?php

namespace App\Containers\AppSection\Auth\UI\API\Controllers;

use App\Containers\AppSection\Auth\Actions\ConfirmDeleteUserAction;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class ConfirmDeleteUserController extends ApiController
{
    public function __invoke(Request $request, ConfirmDeleteUserAction $action)
    {
        $data = $action->run($request);

        return response()->json([
            'success' => true,
            'message' => "Đã gửi otp thành công.",
            'data' => null
        ]);
    }
}

<?php

namespace App\Containers\AppSection\Authentication\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\Authentication\Actions\RegisterUserAction;
use App\Containers\AppSection\Authentication\UI\API\Requests\RegisterUserRequest;
use App\Containers\AppSection\User\UI\API\Transformers\UserTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class RegisterUserController extends ApiController
{
    public function __invoke(RegisterUserRequest $request, RegisterUserAction $action): JsonResponse
    {
        $user = $action->transactionalRun($request->sanitize([
            'email',
            'password', 
            'phone',
            'first_name',
            'last_name',
            'birth',
            'gender',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công. Vui lòng xác thực số điện thoại với mã OTP đã được gửi.',
            'data' => [
                'user_id' => $user->id,
                'phone' => $user->phone,
                'email' => $user->email,
                'name' => $user->name,
                'created_at' => $user->created_at,
            ]
        ], 201);
    }
}

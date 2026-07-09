<?php

namespace App\Containers\AdminSection\User\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AdminSection\Order\UI\API\Transformers\OrderTransformer;
use App\Containers\AdminSection\User\Actions\GetAllOrderByUserIdAction;
use App\Containers\AdminSection\User\Actions\UpdateStatusUserAction;
use App\Containers\AdminSection\User\UI\API\Requests\GetAllOrderByUserIdRequest;
use App\Containers\AdminSection\User\UI\API\Requests\UpdateStatusUserRequest;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UserController extends ApiController
{
    public function updateBlockUser(UpdateStatusUserRequest $request, UpdateStatusUserAction $action)
    {
        $user = User::find($request->id);
        if(!$user) {
            throw new  NotFoundHttpException('User not found');
        }
        $action->run($user);
        return response()->json([
            'is_blocked' => $user->is_blocked,
        ], 200);
    }

    public function getAllOrderByUserId(GetAllOrderByUserIdRequest $request, GetAllOrderByUserIdAction $action)
    {

        $userId = $request->id;
        $pageSize = $request['page_size'] ?? 10;
        $orders = $action->run($userId, $pageSize);
        return Response::create($orders, OrderTransformer::class)->ok();
    }


}

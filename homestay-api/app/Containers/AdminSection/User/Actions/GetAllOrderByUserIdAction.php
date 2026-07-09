<?php

namespace App\Containers\AdminSection\User\Actions;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Actions\Action as ParentAction;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class GetAllOrderByUserIdAction extends ParentAction
{
    public function run($userId, $pageSize )
    {
        $user = User::find($userId);

        if(!$user) {
            throw new  NotFoundHttpException('User not found');
        }

        return $user->orders()->paginate($pageSize);
    }
}

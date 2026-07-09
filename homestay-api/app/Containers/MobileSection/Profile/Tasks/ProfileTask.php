<?php

namespace App\Containers\MobileSection\Profile\Tasks;

use App\Containers\AppSection\User\Data\Repositories\UserRepository;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ProfileTask extends ParentTask
{
    public function __construct(
        protected UserRepository $userRepository,
    )
    {
    }

    public function run(string $userId) : User
    {
        $user = $this->userRepository->find($userId);
        if (empty($user)) {
            throw new NotFoundHttpException('User not found');
        }
        return $user;
    }
}

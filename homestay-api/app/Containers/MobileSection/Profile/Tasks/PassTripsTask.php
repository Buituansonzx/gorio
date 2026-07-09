<?php

namespace App\Containers\MobileSection\Profile\Tasks;

use App\Containers\AppSection\User\Data\Repositories\UserRepository;
use App\Containers\MobileSection\Profile\Data\Repositories\GetPassTripsRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PassTripsTask extends ParentTask
{
    public function __construct(
        private readonly GetPassTripsRepository $passTripsRepository,
        private readonly UserRepository $userRepository
    ) {
    }

    public function run(string $userId)
    {
        $user = $this->userRepository->find($userId);
        if (empty($user)) {
            throw new NotFoundHttpException('User not found');
        }

        return $this->passTripsRepository->listingPassTrips($userId);
    }
}

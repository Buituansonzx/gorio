<?php

namespace App\Containers\SharedSection\Profile\Tasks;

use App\Containers\SharedSection\Profile\Data\Repositories\GetProfileHostRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class GetProfileHostTask extends ParentTask
{
    public function __construct(private readonly GetProfileHostRepository $repository)
    {
    }

    public function run(string $hostId)
    {
        $profile = $this->repository->findProfile($hostId);
        if (empty($profile)) {
            throw new NotFoundHttpException('Profile not found');
        }
        return $profile;
    }
}

<?php

namespace App\Containers\SharedSection\Profile\Tasks;

use App\Containers\SharedSection\Profile\Data\Repositories\ReviewRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class GetReviewTask extends ParentTask
{
    public function __construct(private readonly ReviewRepository $repository)
    {
    }

    public function run(string $hostId)
    {
        return $this->repository->getReviewByHostId($hostId);
    }
}

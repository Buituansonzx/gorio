<?php

namespace App\Containers\SharedSection\Order\Tasks;

use App\Containers\SharedSection\Order\Data\Repositories\ReviewRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateReviewTask extends ParentTask
{
    public function __construct(private readonly ReviewRepository $reviewRepository)
    {
    }

    public function run($reviewData)
    {
        $userId = auth()->user()->id;
        $reviewData['user_id'] = $userId;
        return $this->reviewRepository->create($reviewData);
    }
}

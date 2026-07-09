<?php

namespace App\Containers\SharedSection\Profile\Data\Repositories;

use App\Containers\SharedSection\Room\Models\Review;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Review
 *
 * @extends ParentRepository<TModel>
 */
final class ReviewRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    public function model(): string
    {
        return Review::class;
    }

    public function getReviewByHostId(string $hostId)
    {
        return $this->model
            ->with(['user', 'order.room'])
            ->whereHas('order.room', function ($query) use ($hostId) {
                $query->where('host_id', $hostId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);
    }
}

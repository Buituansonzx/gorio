<?php

namespace App\Containers\SharedSection\Order\Data\Repositories;

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

    public function create($data)
    {
        $review  = Review::create([
            'order_id' => $data['order_id'],
            'rating' => $data['rating'],
            'content' => $data['content'] ?? null,
            'cleanliness_rating' => $data['cleanliness_rating'],
            'accuracy_rating' => $data['accuracy_rating'],
            'communication_rating' => $data['communication_rating'],
            'location_rating' => $data['location_rating'],
            'checkin_rating' => $data['checkin_rating'],
            'value_rating' => $data['value_rating'],
            'user_id' => $data['user_id'],
        ]);
        return $review;
    }
}

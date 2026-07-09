<?php

namespace App\Containers\SharedSection\Order\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\Review;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ReviewTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Review $review): array
    {
        return [
            'type' => $review->getResourceKey(),
            'id' => $review->id,
            'order_id' => $review->order_id,
            'rating' => $review->rating,
            'content' => $review->content,
            'cleanliness_rating' => $review->cleanliness_rating,
            'accuracy_rating' => $review->accuracy_rating,
            'communication_rating' => $review->communication_rating,
            'location_rating' => $review->location_rating,
            'check_in_rating' => $review->check_in_rating,
            'value_rating' => $review->value_rating,
            'user_id' => $review->user_id,
            'created_at' => $review->created_at,
            'updated_at' => $review->updated_at,
        ];
    }
}

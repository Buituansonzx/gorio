<?php

namespace App\Containers\MobileSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\Review;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ReviewByRoomIdTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Review $review): array
    {
        return [
            'type' => $review->getResourceKey(),
            'id' => $review->id,
            'user_name' => $review->user->first_name . ' ' . $review->user->last_name,
            'user_avatar' => S3Helper::getS3ImageUrl($review->user->avatar),
            'rating' => $review->rating,
            'content' => $review->content,
            'created_at' => $review->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

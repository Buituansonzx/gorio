<?php

namespace App\Containers\SharedSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateReviewRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'order_id' => 'required|string|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string|max:10000000',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'accuracy_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'location_rating' => 'required|integer|min:1|max:5',
            'checkin_rating' => 'required|integer|min:1|max:5',
            'value_rating' => 'required|integer|min:1|max:5',
        ];
    }
}

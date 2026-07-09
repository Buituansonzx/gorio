<?php

namespace App\Containers\AdminSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListingOrderRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'page_size' => 'int|nullable',
            'key' => 'string|nullable',
            'status' => 'nullable|in:pending,paid,expired,cancelled',
            'room_id' => 'string|nullable',
            'created_from' => 'date|nullable',
        ];
    }
}

<?php

namespace App\Containers\AppSection\Notification\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListNotificationsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'type' => 'sometimes|string',
            'page_size' => 'sometimes|integer|min:1|max:100',
        ];
    }
}

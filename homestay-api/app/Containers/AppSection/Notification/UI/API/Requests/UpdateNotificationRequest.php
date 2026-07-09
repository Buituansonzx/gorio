<?php

namespace App\Containers\AppSection\Notification\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateNotificationRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}

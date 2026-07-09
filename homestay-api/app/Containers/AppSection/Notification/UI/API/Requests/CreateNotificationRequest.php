<?php

namespace App\Containers\AppSection\Notification\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateNotificationRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

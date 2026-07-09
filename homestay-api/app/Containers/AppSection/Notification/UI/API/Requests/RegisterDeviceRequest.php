<?php

namespace App\Containers\AppSection\Notification\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class RegisterDeviceRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'player_id' => ['required', 'uuid'],
        ];
    }
}

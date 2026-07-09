<?php

namespace App\Containers\AdminSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateRoomOrderRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'search' => 'required|string',
        ];
    }
}

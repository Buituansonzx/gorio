<?php

namespace App\Containers\AdminSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateRoomRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'title' => 'sometimes|string',
            'file' => 'sometimes|file|mimes:jpg,jpeg,png,gif|max:51200',
            'checkin_instruction' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
        ];
    }
}

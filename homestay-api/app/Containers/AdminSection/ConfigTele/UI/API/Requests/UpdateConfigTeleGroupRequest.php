<?php

namespace App\Containers\AdminSection\ConfigTele\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class UpdateConfigTeleGroupRequest extends ParentRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'chat_id' => 'sometimes|string|max:255',
            'bot_token' => 'sometimes|string|max:255',
            'is_active' => 'sometimes|boolean',
        ];
    }
}

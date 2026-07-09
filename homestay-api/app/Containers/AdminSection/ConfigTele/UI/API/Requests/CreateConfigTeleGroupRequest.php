<?php

namespace App\Containers\AdminSection\ConfigTele\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class CreateConfigTeleGroupRequest extends ParentRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'chat_id' => 'required|string|max:255',
            'bot_token' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }
}

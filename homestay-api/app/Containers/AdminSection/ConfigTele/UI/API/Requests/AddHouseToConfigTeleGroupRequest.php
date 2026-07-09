<?php

namespace App\Containers\AdminSection\ConfigTele\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class AddHouseToConfigTeleGroupRequest extends ParentRequest
{
    public function rules(): array
    {
        return [
            'house_ids' => 'required|array',
            'house_ids.*' => 'exists:houses,id',
        ];
    }
}

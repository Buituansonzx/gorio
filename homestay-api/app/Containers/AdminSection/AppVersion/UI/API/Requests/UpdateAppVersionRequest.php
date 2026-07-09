<?php

namespace App\Containers\AdminSection\AppVersion\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class UpdateAppVersionRequest extends ParentRequest
{
    public function rules(): array
    {
        return [
            'platform' => 'in:android,ios',
            'version_name' => 'string',
            'version_code' => 'integer',
            'is_force_update' => 'boolean',
            'update_url' => 'nullable|string',
            'title' => 'nullable|array',
            'content' => 'nullable|array',
            'status' => 'nullable|integer',
        ];
    }
}

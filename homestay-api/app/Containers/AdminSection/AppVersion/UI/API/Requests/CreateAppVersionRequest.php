<?php

namespace App\Containers\AdminSection\AppVersion\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class CreateAppVersionRequest extends ParentRequest
{
    public function rules(): array
    {
        return [
            'platform' => 'required|in:android,ios',
            'version_name' => 'required|string',
            'version_code' => 'required|integer',
            'is_force_update' => 'boolean',
            'update_url' => 'nullable|string',
            'title' => 'nullable|array',
            'content' => 'nullable|array',
            'status' => 'nullable|integer',
        ];
    }
}

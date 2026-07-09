<?php

namespace App\Containers\MobileSection\AppVersion\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class CheckAppVersionRequest extends ParentRequest
{
    public function rules(): array
    {
        return [
            'platform' => 'required|in:android,ios',
            'version_code' => 'required|integer',
        ];
    }
}

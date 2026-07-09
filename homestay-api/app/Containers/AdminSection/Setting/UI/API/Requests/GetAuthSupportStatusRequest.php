<?php

namespace App\Containers\AdminSection\Setting\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class GetAuthSupportStatusRequest extends ParentRequest
{
    public function rules(): array
    {
        return [];
    }
}

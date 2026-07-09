<?php

namespace App\Containers\ClientSection\Profile\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetProfileRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
        ];
    }
}

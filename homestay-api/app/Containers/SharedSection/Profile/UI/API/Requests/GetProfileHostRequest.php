<?php

namespace App\Containers\SharedSection\Profile\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetProfileHostRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

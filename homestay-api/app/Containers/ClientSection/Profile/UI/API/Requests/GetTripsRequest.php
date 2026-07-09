<?php

namespace App\Containers\ClientSection\Profile\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetTripsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

<?php

namespace App\Containers\MobileSection\Profile\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class PassTripsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

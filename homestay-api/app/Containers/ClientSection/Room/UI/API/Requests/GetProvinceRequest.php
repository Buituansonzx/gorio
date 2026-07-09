<?php

namespace App\Containers\ClientSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetProvinceRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

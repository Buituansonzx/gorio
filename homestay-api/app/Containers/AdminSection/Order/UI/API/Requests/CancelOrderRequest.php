<?php

namespace App\Containers\AdminSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CancelOrderRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

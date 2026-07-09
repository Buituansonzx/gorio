<?php

namespace App\Containers\SharedSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class listVoucherAvailableRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'price_total' => 'required|numeric|min:0',
            'search' => 'sometimes|string',
        ];
    }
}

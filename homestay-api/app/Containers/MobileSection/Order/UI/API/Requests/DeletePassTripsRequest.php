<?php

namespace App\Containers\MobileSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class DeletePassTripsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'order_id' => 'sometimes|string|exists:orders,id',
            'key' => 'sometimes|string',
        ];
    }
}

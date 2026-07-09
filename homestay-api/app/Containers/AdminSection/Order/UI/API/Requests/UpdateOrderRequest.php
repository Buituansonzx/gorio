<?php

namespace App\Containers\AdminSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateOrderRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'room_id' => 'sometimes|string|exists:rooms,id',
            'guest_name' => 'sometimes|string',
            'guest_phone' => 'sometimes|string',
            'number_of_guests' => 'sometimes|integer|min:1',
            'check_in' => 'sometimes|date',
            'check_out' => 'sometimes|date|after:check_in',
            'total' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,paid,cancelled,expired',
        ];
    }
}

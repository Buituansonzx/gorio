<?php

namespace App\Containers\AdminSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateOrderRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'room_id' => 'required|string|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'total' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:2000',
        ];
    }
}

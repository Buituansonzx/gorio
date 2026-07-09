<?php

namespace App\Containers\MobileSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateOrderRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {

        return [
            'user_id' => [
                'sometimes',
                'uuid',
                'exists:users,id',
            ],
            'room_id' => [
                'sometimes',
                'uuid',
                'exists:rooms,id',
            ],
            'check_in' => [
                'sometimes',
                'date_format:Y-m-d H:i:s',
            ],
            'check_out' => [
                'sometimes',
                'date_format:Y-m-d H:i:s',
                'after:check_in',
            ],
            'number_of_guests' => [
                'sometimes',
                'integer',
                'min:1',
            ],
            'guest_name' => [
                'sometimes',
                'string',
                'max:100',
            ],
            'guest_phone' => [
                'sometimes',
                'string',
                'regex:/^\+?[0-9]{8,20}$/',
            ],
            'price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'status' => [
                'sometimes',
                'in:pending,confirmed,cancelled,completed',
            ],
            'note' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }
}

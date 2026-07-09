<?php

namespace App\Containers\ClientSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateOrderRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'room_id' => [
                'required',
                'uuid',
                'exists:rooms,id',
            ],
            'check_in' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
            'check_out' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:check_in',
            ],
            'number_of_guests' => [
                'required',
                'integer',
                'min:1',
            ],
            'guest_name' => [
                'required',
                'string',
                'max:100',
            ],
            'guest_phone' => [
                'required',
                'string',
                'regex:/^\+?[0-9]{8,20}$/',
            ],
            'total' => [
                'required',
                'numeric',
                'min:0',
            ],
            'voucher_code' => [
                'nullable',
                'string',
                'exists:vouchers,code',
            ],
            'note' => [
                'nullable',
                'string',
                'max:500',
            ],
            'buffer' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}

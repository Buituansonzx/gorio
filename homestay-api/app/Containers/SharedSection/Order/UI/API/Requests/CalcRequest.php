<?php

namespace App\Containers\SharedSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CalcRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'check_in'  => 'required|date_format:Y-m-d H:i:s',
            'check_out' => 'required|date_format:Y-m-d H:i:s|after:check_in',
            'adults' => 'required|integer|min:1',
            'room_id' => 'required|string|exists:rooms,id',
        ];
    }
}

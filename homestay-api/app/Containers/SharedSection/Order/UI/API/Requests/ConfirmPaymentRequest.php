<?php

namespace App\Containers\SharedSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;
use Illuminate\Validation\Rule;


final class ConfirmPaymentRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'message' => 'required|string',
            'sender' => ['required', 'string',
                Rule::in(config('app.bank_sms.senders'))
                ],
//            'info'   => 'required|string',
//            'price' => 'required|numeric',
//            'bank_name' => 'required|string',
//            'transferred_at' => 'required|date',
        ];
    }
}

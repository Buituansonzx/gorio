<?php

namespace App\Containers\SharedSection\Order\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CheckVoucherRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'code' => 'required|exists:vouchers,code',
        ];
    }
    public function messages()
    {
        return [
            'code.required' => __('voucher.code_required'),
            'code.exists'   => __('voucher.code_not_found'),
        ];
    }
}

<?php

namespace App\Containers\AdminSection\Voucher\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateVoucherRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:vouchers,code',
            'name' => 'required|string',
            'image' => 'sometimes|nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'description' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_amount' => 'sometimes|nullable|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'type' => 'required|in:public,private'
        ];
    }
}

<?php

namespace App\Containers\AdminSection\Voucher\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateVoucherRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'code' => 'sometimes|string',
            'name' => 'sometimes|string',
            'image' => 'sometimes|nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'description' => 'sometimes|nullable|string',
            'discount_type' => 'sometimes|in:percentage,fixed',
            'discount_value' => 'sometimes|numeric|min:0',
            'max_discount_amount' => 'sometimes|nullable|numeric|min:0',
            'min_order_amount' => 'sometimes|nullable|numeric|min:0',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'usage_limit' => 'sometimes|nullable|integer|min:1',
            'quantity' => 'sometimes|nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
            'type' => 'sometimes|in:public,private'
        ];
    }
}

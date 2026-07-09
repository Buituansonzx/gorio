<?php

namespace App\Containers\AdminSection\Voucher\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListVoucherRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'key' => 'sometimes|string',
            'status' => 'sometimes|integer',
            'type' => 'sometimes|in:percentage,fixed',
            'page_size' => 'sometimes|integer|min:1|max:100',
        ];
    }
}

<?php

namespace App\Containers\AdminSection\House\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class StoreSpecialOfferRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'last_minute_hours' => 'required|integer|min:0|max:24',
            'last_minute_discount_percent' => 'required|integer|min:0|max:100',
        ];
    }
}

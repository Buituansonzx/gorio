<?php

namespace App\Containers\AdminSection\Host\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateHostRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone_number',
            'address' => 'required|string',
            'description' => 'sometimes|string',
        ];
    }
}

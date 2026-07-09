<?php

namespace App\Containers\AdminSection\Host\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateHostRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'brand_name'   => 'nullable|string|max:255',
            'first_name'   => 'nullable|string|max:255',
            'last_name'    => 'nullable|string|max:255',
            'description'  => 'nullable|string|max:3000',

            'avatar'       => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',

            'email'        => 'nullable|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20|unique:users,phone_number,' . $id,

            'is_active'    => 'nullable|boolean',
        ];
    }
}

<?php

namespace App\Containers\AppSection\User\UI\API\Requests;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateUserRequest extends ParentRequest
{

    public function rules()
    {
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name'  => 'sometimes|string|max:255',
            'avatar' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'email' => 'sometimes|email',
            'gender' => 'sometimes|in:male,female',
            'birth' => 'sometimes|date_format:Y-m-d',
        ];
    }
}

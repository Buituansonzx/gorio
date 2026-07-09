<?php

namespace App\Containers\SharedSection\Profile\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UploadAvatarHostRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}

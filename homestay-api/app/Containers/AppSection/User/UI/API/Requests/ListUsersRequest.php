<?php

namespace App\Containers\AppSection\User\UI\API\Requests;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListUsersRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'keyword' => 'string|nullable',
            'status' => 'int|nullable',
            'page_size' => 'int|nullable',
        ];
    }

//    public function authorize(): bool
//    {
//        dd($this->user());
//        return $this->user()->isAdmin();
//    }
}

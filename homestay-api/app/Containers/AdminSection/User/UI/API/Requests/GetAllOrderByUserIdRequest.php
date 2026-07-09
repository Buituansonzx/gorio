<?php

namespace App\Containers\AdminSection\User\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetAllOrderByUserIdRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'page_size' => 'int|nullable',
        ];
    }
}

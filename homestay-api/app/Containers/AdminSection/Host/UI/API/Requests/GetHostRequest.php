<?php

namespace App\Containers\AdminSection\Host\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetHostRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'page_size' => 'int|nullable',
            'keyword' => 'string|nullable',
            'status' => 'int|nullable',
        ];
    }
}

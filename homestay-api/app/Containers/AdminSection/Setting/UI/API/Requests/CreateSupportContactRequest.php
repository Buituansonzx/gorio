<?php

namespace App\Containers\AdminSection\Setting\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class CreateSupportContactRequest extends ParentRequest
{
    public function rules(): array
    {
        return [
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ];
    }

}

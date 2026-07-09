<?php

namespace App\Containers\AdminSection\Setting\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class UpdateSupportContactRequest extends ParentRequest
{
    public function rules(): array
    {
        return [
            'label' => 'sometimes|string|max:255',
            'value' => 'sometimes|string|max:255',
        ];
    }

}

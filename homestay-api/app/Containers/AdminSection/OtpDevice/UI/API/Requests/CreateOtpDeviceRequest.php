<?php

namespace App\Containers\AdminSection\OtpDevice\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class CreateOtpDeviceRequest extends ParentRequest
{
    public function rules(): array
    {
        return [
            'device_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ];
    }

}

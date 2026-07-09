<?php

namespace App\Containers\ClientSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class CheckTimeoutRequest extends ParentRequest
{
    /**
     * Define which Roles and/or Permissions has access to this request.
     */
    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     */
    protected array $decode = [];

    /**
     * Defining the URL parameters allows applying validation rules on them.
     */
    protected array $urlParameters = [];

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'duration' => 'sometimes|integer|min:1|max:300', // Max 5 minutes timeout test
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     * Return true to make this API public (no auth required)
     */
    public function authorize(): bool
    {
        return true; // Public API - no authentication required
    }
}

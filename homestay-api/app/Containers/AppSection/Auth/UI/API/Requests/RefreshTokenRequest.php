<?php

namespace App\Containers\AppSection\Auth\UI\API\Requests;

use App\Ship\Parents\Requests\Request;

class RefreshTokenRequest extends Request
{
    /**
     * The rules that should be applied to the request.
     */
    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'refresh_token.required' => 'Refresh token là bắt buộc.',
            'refresh_token.string' => 'Refresh token phải là chuỗi ký tự.',
        ];
    }
}
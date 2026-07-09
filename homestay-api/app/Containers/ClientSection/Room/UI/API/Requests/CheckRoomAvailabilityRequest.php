<?php

namespace App\Containers\ClientSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class CheckRoomAvailabilityRequest extends ParentRequest
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
    protected array $decode = [
        'id',
    ];

    /**
     * Defining the URL parameters (e.g, `/user/{id}`) allows applying
     * validation rules on them and allows accessing them like request data.
     */
    protected array $urlParameters = [
        'id',
    ];

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'sometimes|integer|min:1|max:20',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->check([
            'hasAccess',
        ]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'id.exists' => 'Phòng không tồn tại.',
            'check_in.required' => 'Ngày check-in là bắt buộc.',
            'check_in.after_or_equal' => 'Ngày check-in phải từ hôm nay trở đi.',
            'check_out.required' => 'Ngày check-out là bắt buộc.',
            'check_out.after' => 'Ngày check-out phải sau ngày check-in.',
            'guests.min' => 'Số khách tối thiểu là 1.',
            'guests.max' => 'Số khách tối đa là 20.',
        ];
    }
}

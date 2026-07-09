<?php

namespace App\Containers\AppSection\Authentication\UI\API\Requests;

use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Requests\Request as ParentRequest;
use App\Ship\Helpers\PhoneHelper;
use Illuminate\Validation\Rule;

final class RegisterUserRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            // Phone validation - support both object and string formats
            'phone' => ['required', function ($attribute, $value, $fail) {

                // Chuẩn hóa phone về E.164
                if (is_array($value)) {
                    if (!isset($value['country_code']) || !isset($value['phone_number'])) {
                        $fail('Phone object must contain country_code and phone_number');
                        return;
                    }

                    $normalizedPhone = PhoneHelper::formatToE164(
                        $value['phone_number'],
                        $value['country_code']
                    );
                } else {
                    // String format: "090..." hoặc "+8490..."
                    $normalizedPhone = PhoneHelper::formatToE164(
                        (string)$value,
                        'VN'
                    );
                }

                if (!$normalizedPhone) {
                    $fail('Invalid phone number format');
                    return;
                }
                // Inject normalized phone vào request để dùng tiếp ở Controller
                $this->merge(['normalized_phone' => $normalizedPhone]);
                // Kiểm tra trùng số
                if (User::withTrashed()->where('phone', $normalizedPhone)->exists()) {
                    $fail('This phone number is already registered');
                }
            }],

            // Personal information
            'first_name' => 'required|string|min:1|max:50',
            'last_name' => 'required|string|min:1|max:50',
            'birth' => 'required|date|before:today',
            'email' => 'required|email|unique:users,email',
            'gender' => [Rule::enum(Gender::class), 'nullable'],

            // Password is optional - will be auto-generated if not provided
            'password' => 'nullable|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required',
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'birth.required' => 'Date of birth is required',
            'birth.before' => 'Date of birth must be in the past',
            'email.required' => 'Email is required',
            'email.unique' => 'This email is already registered',
        ];
    }
}

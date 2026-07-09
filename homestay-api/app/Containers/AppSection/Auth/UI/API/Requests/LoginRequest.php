<?php

namespace App\Containers\AppSection\Auth\UI\API\Requests;

use App\Ship\Parents\Requests\Request;
use App\Ship\Helpers\PhoneHelper;
use Propaganistas\LaravelPhone\PhoneNumber;

class LoginRequest extends Request
{
    public function rules(): array
    {
        $phoneInput = $this->input('phone');
        $otp = $this->input('otp');
        
        // Custom OTP validation: allow 000000 to auto pass or require exactly 6 digits
        // TODO: Remove auto pass OTP (000000) when SMS OTP service is implemented
        $otpRules = ['required', 'string'];
        if ($otp !== '000000') {
            $otpRules[] = 'size:6';
            $otpRules[] = 'regex:/^\d{6}$/'; // Only digits for normal OTP
        } else {
            $otpRules[] = 'size:6'; // Still require 6 characters for 000000
        }
        
        // Nếu phone là object { country_code, phone_number }
        if (is_array($phoneInput) && isset($phoneInput['country_code'], $phoneInput['phone_number'])) {
            $countryCode = $phoneInput['country_code'];
            $validateCode = 'phone:' . $countryCode;
            
            return [
                'phone.country_code' => ['required', 'string'],
                'phone.phone_number' => ['required', $validateCode],
                'otp' => $otpRules,
            ];
        }
        
        // Nếu phone là string (backward compatibility)
        $code = $this->getPhoneAnalysis()['country_code'] ?? 'VN';
        $validateCode = 'phone:' . $code;
        
        return [
            'phone' => ['required', $validateCode],
            'otp' => $otpRules,
        ];
    }

    /**
     * After validation hook: if phone is valid, normalize it to E.164 in the request.
     */
    protected function passedValidation(): void
    {
        $normalized = $this->getNormalizedPhone();
        if ($normalized) {
            // Replace the phone in the request with the normalized value
            $this->merge(['phone' => $normalized]);
        }
    }

    /**
     * Check if OTP is auto pass (000000)
     * TODO: Remove this method when SMS OTP service is implemented
     * @return bool
     */
    public function isAutoPassOtp(): bool
    {
        return $this->input('otp') === '000000';
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.phone' => 'Số điện thoại không hợp lệ. Hỗ trợ định dạng: 0xxxxxxxxx (VN) hoặc +xxxxxxxxxxxx (quốc tế).',
            'phone.country_code.required' => 'Mã quốc gia là bắt buộc.',
            'phone.country_code.string' => 'Mã quốc gia phải là chuỗi ký tự.',
            'phone.phone_number.required' => 'Số điện thoại là bắt buộc.',
            'phone.phone_number.phone' => 'Số điện thoại không hợp lệ.',
            'otp.required' => 'Mã OTP là bắt buộc.',
            'otp.string' => 'Mã OTP phải là chuỗi ký tự.',
            'otp.size' => 'Mã OTP phải có đúng 6 ký tự.',
            'otp.regex' => 'Mã OTP chỉ được chứa các chữ số.',
        ];
    }

    /**
     * Phân tích và chuẩn hóa số điện thoại sau khi validate - hỗ trợ cả string và object
     * @return array
     */
    public function getPhoneAnalysis(): array
    {
        $phoneInput = $this->input('phone');
        
        if (!$phoneInput) {
            return ['error' => 'Không có số điện thoại'];
        }

        // Nếu client gửi object { country_code, phone_number }
        if (is_array($phoneInput) && isset($phoneInput['country_code'], $phoneInput['phone_number'])) {
            $countryCode = $phoneInput['country_code'];
            $phoneNumber = (string)$phoneInput['phone_number'];
            
            if (!$phoneNumber) {
                return ['error' => 'Không có phone_number trong payload'];
            }
            
            return PhoneHelper::analyzePhone($phoneNumber, $countryCode);
        }

        // Nếu client gửi string (backward compatibility)
        return PhoneHelper::analyzePhone((string)$phoneInput);
    }

    /**
     * Lấy số điện thoại đã được chuẩn hóa theo format E.164 - hỗ trợ cả string và object
     * @return string|null
     */
    public function getNormalizedPhone(): ?string
    {
        $phoneInput = $this->input('phone');
        
        if (!$phoneInput) {
            return null;
        }

        // Nếu client gửi object { country_code, phone_number }
        if (is_array($phoneInput) && isset($phoneInput['country_code'], $phoneInput['phone_number'])) {
            $countryCode = $phoneInput['country_code'];
            $phoneNumber = (string)$phoneInput['phone_number'];
            
            if (!$phoneNumber) {
                return null;
            }
            
            return PhoneHelper::formatToE164($phoneNumber, $countryCode);
        }

        // Nếu client gửi string (backward compatibility)
        return PhoneHelper::formatToE164((string)$phoneInput);
    }
}

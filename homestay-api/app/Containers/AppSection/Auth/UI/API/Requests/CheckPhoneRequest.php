<?php

namespace App\Containers\AppSection\Auth\UI\API\Requests;

use App\Ship\Parents\Requests\Request;
use App\Ship\Helpers\PhoneHelper;

class CheckPhoneRequest extends Request
{
    public function rules(): array
    {
        $phoneInput = $this->input('phone');
        
        // Nếu phone là object { country_code, phone_number }
        if (is_array($phoneInput) && isset($phoneInput['country_code'], $phoneInput['phone_number'])) {
            $countryCode = $phoneInput['country_code'];
            $validateCode = 'phone:' . $countryCode;
            
            return [
                'phone.country_code' => ['required', 'string'],
                'phone.phone_number' => ['required', $validateCode],
            ];
        }
        
        // Nếu phone là string (backward compatibility)
        $code = $this->getPhoneAnalysis()['country_code'] ?? 'VN';
        $validateCode = 'phone:' . $code;
        
        return [
            'phone' => ['required', $validateCode],
        ];
    }

    /**
     * After validation hook: normalize phone to E.164 format
     */
    protected function passedValidation(): void
    {
        $normalized = $this->getNormalizedPhone();
        if ($normalized) {
            $this->merge(['phone' => $normalized]);
        }
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
        ];
    }

    /**
     * Phân tích số điện thoại - hỗ trợ cả string và object
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
     * Lấy số điện thoại đã chuẩn hóa theo E.164 - hỗ trợ cả string và object
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

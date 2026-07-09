<?php

namespace App\Containers\AppSection\Auth\UI\API\Controllers;

use App\Containers\AppSection\Auth\Actions\LoginAction;
use App\Containers\AppSection\Auth\UI\API\Requests\LoginRequest;
use App\Ship\Parents\Controllers\ApiController;

class LoginController extends ApiController
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        // Add auto pass flag to data
        // TODO: Remove auto pass logic when SMS OTP service is implemented
        $data['is_auto_pass'] = $request->isAutoPassOtp();

        // Lấy thông tin chi tiết về số điện thoại
        $phoneAnalysis = $request->getPhoneAnalysis();

        // Thêm thông tin country và phone details vào data
        if ($phoneAnalysis['success']) {
            $data['phone_analysis'] = $phoneAnalysis;
            $data['country_code'] = $phoneAnalysis['country_code'];
            $data['phone_code'] = '+' . $phoneAnalysis['international_code'];

            // Lấy phone_number từ payload gốc
            $phoneInput = $request->input('phone');
            if (is_array($phoneInput) && isset($phoneInput['phone_number'])) {
                $data['phone_number'] = (string)$phoneInput['phone_number'];
            } else {
                // Nếu là string format, extract số local từ normalized phone
                $normalizedPhone = $data['phone'];
                $data['phone_number'] = ltrim($normalizedPhone, '+' . $phoneAnalysis['international_code']);
            }
        }

        $result = app(LoginAction::class)->run($data);
        // Thêm thông tin số điện thoại vào response
        if ($phoneAnalysis['success']) {
            $result['phone_info'] = [
                'original' => $phoneAnalysis['original'],
                'country' => $phoneAnalysis['country_name'] . ' (' . $phoneAnalysis['country_code'] . ')',
                'normalized' => $phoneAnalysis['formatted']['e164'],
                'type' => $phoneAnalysis['validation']['type'],
                'is_mobile' => $phoneAnalysis['validation']['is_mobile'],
            ];
        }

        return response()->json($result);
    }
}

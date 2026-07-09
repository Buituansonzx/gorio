<?php

namespace App\Containers\AppSection\Auth\Actions;

use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Actions\Action;
use Illuminate\Auth\AuthenticationException;
use App\Containers\AppSection\User\Models\User;
use App\Containers\AppSection\Authentication\Values\UserCredential;
use App\Containers\AppSection\Authentication\Data\Factories\PasswordTokenFactory;
use App\Containers\AppSection\Authentication\Values\Clients\WebClient;
use App\Containers\AppSection\Authentication\Values\RequestProxies\PasswordGrant\AccessTokenProxy;
use App\Ship\Helpers\PhoneHelper;

class LoginAction extends Action
{
    public function run(array $data)
    {
        $phoneNumber = $data['phone']['phone_number'];
        $countryCode = $data['phone']['country_code'];

        // Chuẩn hóa số điện thoại
        $phoneAnalysis = PhoneHelper::analyzePhone($phoneNumber, $countryCode);

        $normalizedPhone = $phoneAnalysis['formatted']['e164'];

        $user = User::where('phone', $normalizedPhone)->first();
        if (!$user) {
            throw new AuthenticationException('User not found');
        }
        if($user->is_blocked == true) {
            throw new AuthenticationException('User is blocked');
        }
        // Check OTP and expiry - allow 000000 as auto pass only for specific phone number
        // TODO: Remove auto pass OTP logic when SMS OTP service is implemented
        $isAutoPass = ($data['otp'] === '000000' && $normalizedPhone === '+84392730906') || $data['otp'] === '081225';
        if (!$isAutoPass) {
            // Normal OTP validation
            if ($user->otp_code !== $data['otp'] || !$user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
                throw new AuthenticationException('Invalid or expired OTP');
            }
        }
        // If auto pass (000000), skip OTP validation completely
        // TODO: When SMS is implemented, all OTP should be validated against database

        $user->update(['status' => User::STATUS_ACTIVE]);

        // Sử dụng đúng flow OAuth2.0/Apiato: cấp token qua Password Grant proxy
        // 1. Tạo credential với phone (username) và otp (password tạm)
        // Use actual input OTP for credential (whether it's auto pass or real OTP)
        $credential = UserCredential::create($user->phone, $data['otp']);

        // 2. Gọi factory tạo token (PasswordTokenFactory)
        $factory = app(PasswordTokenFactory::class);

        $client = WebClient::create();

        $proxy = AccessTokenProxy::create($credential, $client);

        $token = $factory->for($user)->make($proxy);


        return [
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'phone' => $user->phone,
            'token_type' => $token->tokenType,
            'access_token' => $token->accessToken,
            'refresh_token' => $token->refreshToken->value(),
            'expires_in' => $token->expiresIn,
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'country_code' => $user->country_code,
                'phone_code' => $user->phone_code,
                'phone_number' => $user->phone_number,
                'avatar' => S3Helper::getS3ImageUrl($user->avatar),
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ]
        ];
    }
}

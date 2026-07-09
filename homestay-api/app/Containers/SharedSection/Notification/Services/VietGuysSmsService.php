<?php

namespace App\Containers\SharedSection\Notification\Services;

use Illuminate\Support\Facades\Http;

/**
 * TODO: Integrate this SMS service with OTP generation and login flow
 * Currently OTP is generated but not sent via SMS
 */
class VietGuysSmsService
{
    private function getAccessToken()
    {
        $response = Http::asForm()->post(config('vietguys.api_url') . '/connect/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('vietguys.username'),
            'client_secret' => config('vietguys.password'),
            'scope' => 'send_brandname_otp send_brandname_notify',
        ]);
        $data = $response->json();
        if (!isset($data['access_token'])) {
            throw new \Exception('Lấy access token từ VietGuys thất bại: ' . json_encode($data));
        }
        return $data['access_token'];
    }

    public function send($phone, $message)
    {
        $accessToken = $this->getAccessToken();

        $payload = [
            'BrandName' => config('vietguys.brandname'),
            'Phone' => $phone,
            'Message' => $message,
            'Type' => 2, // 1: Notify, 2: OTP
            'RequestId' => uniqid('vg_', true),
        ];

        $response = Http::withToken($accessToken)
            ->post(config('vietguys.api_url') . '/api/sms/brandname/submit', $payload);

        return $response->json();
    }
}

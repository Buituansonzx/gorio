<?php

namespace App\Jobs;

use App\Ship\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SendOtpSmsJob implements ShouldQueue
{
    use Queueable;

    public string $phone;
    public string $otp;
    public int $tries = 3;
    public int $timeout = 30;
    /**
     * Create a new job instance.
     */
    public function __construct(string $phone, string $otp)
    {
        $this->phone = $phone;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     */
    public function handle(SmsService $smsService): void
    {
        $templates = [
            "Mã OTP của bạn là: {$this->otp}.",
            "Xác nhận OTP: {$this->otp}. Mã có hiệu lực trong 5 phút.",
            "Mã số bí mật của bạn là: {$this->otp}.",
            "OTP từ hệ thống: {$this->otp}."
        ];

        $content = Arr::random($templates);

        $result = $smsService->send($this->phone, $content);

        if ($result['ok']) {
            Log::info('SendOtpSmsJob: success', ['phone' => $this->phone]);
            return;
        }

        Log::warning('SendOtpSmsJob: failed result', [
            'phone' => $this->phone,
            'result' => $result,
        ]);
    }
    public function failed(\Throwable $exception): void
    {
        Log::error('SendOtpSmsJob failed permanently', [
            'phone' => $this->phone,
            'otp' => $this->otp,
            'error' => $exception->getMessage(),
        ]);
    }
}

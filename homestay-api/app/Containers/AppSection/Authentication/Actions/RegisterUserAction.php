<?php

namespace App\Containers\AppSection\Authentication\Actions;

use App\Containers\AppSection\User\Models\User;
use App\Containers\AppSection\User\Tasks\CreateUserTask;
use App\Jobs\SendOtpSmsJob;
use App\Jobs\SendTelegramViewJob;
use App\Ship\Parents\Actions\Action as ParentAction;
use App\Ship\Helpers\PhoneHelper;
use App\Ship\Services\TelegramService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

final class RegisterUserAction extends ParentAction
{
    public function __construct(
        private readonly CreateUserTask $createUserTask,
    ) {
    }

    public function run(array $data): User
    {
        // Xử lý phone number - validation đã được thực hiện trong Request
        if (isset($data['phone'])) {
            $phoneAnalysis = PhoneHelper::analyzePhone(
                $data['phone']['phone_number'],
                $data['phone']['country_code']
            );

            $data['phone'] = $phoneAnalysis['formatted']['e164'];
            $data['country_code'] = $phoneAnalysis['country_code'];
            $data['phone_code'] = $phoneAnalysis['international_code'];
            $data['phone_number'] = $phoneAnalysis['formatted']['national'];
        }

        // Auto-generate password nếu không có
        if (!isset($data['password']) || empty($data['password'])) {
            $data['password'] = Str::random(12);
        }

        // Tạo full name từ first_name + last_name
        $data['name'] = "{$data['first_name']} {$data['last_name']}";

        // Tự động tạo OTP cho xác thực số điện thoại
        // TODO: Send OTP via SMS when SMS service is implemented
        $data['otp_code'] = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $data['otp_expires_at'] = now()->addMinutes(5); // OTP có hiệu lực 5 phút

        $user = $this->createUserTask->run($data);

        event(new Registered($user));
        SendOtpSmsJob::dispatch($data['phone'], $data['otp_code']);
        $tokenTelegram = TelegramService::tokenSendMessage(User::CODE_SEND_OTP);
        SendTelegramViewJob::dispatch(
            'telegram.otp',
            [
                'action' =>  "Đăng ký",
                'phone_number' => $data['phone'],
                'user_name' => $user->name,
                'otp_code' => $data['otp_code'],
            ],
            $tokenTelegram['token'],
            $tokenTelegram['chatId']
        );

        return $user;
    }
}

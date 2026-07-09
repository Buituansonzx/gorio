<?php

namespace App\Containers\AppSection\Auth\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Jobs\SendOtpSmsJob;
use App\Jobs\SendTelegramViewJob;
use App\Ship\Parents\Actions\Action;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Helpers\PhoneHelper;
use App\Ship\Services\TelegramService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckPhoneAction extends Action
{
    public function run(array $data)
    {
        $phoneNumber = $data['phone']['phone_number'];
        $countryCode = $data['phone']['country_code'];

        // Chuẩn hóa số điện thoại
        $phoneAnalysis = PhoneHelper::analyzePhone($phoneNumber, $countryCode);
        $phoneNumber = $phoneAnalysis['formatted']['e164'];
        $normalizedPhone = $phoneAnalysis['formatted']['e164'];
        $internationalCode = $phoneAnalysis['international_code'] ?? null;
        $user = User::where('phone', $normalizedPhone)->first();
        if ($user !== null && $user->is_blocked === true) {
            throw new AuthenticationException('User is blocked');
        }

        // trường hợp user đã tồn tại tiến hành sinh ra mã code xác nhận
        // và update vao bản user
        if ($user) {
            $now = now();
            
            // Check locked out status
            if ($user->locked_until && $now->lessThan($user->locked_until)) {
                throw new HttpResponseException(response()->json([
                    'success' => false,
                    'message' => 'Bạn đã yêu cầu mã xác minh quá nhiều lần. Vui lòng thử lại sau 10 phút'
                ], 429));
            }

            // Mở khóa nếu đã qua thời gian locked_until, đồng thời reset đếm
            if ($user->locked_until && $now->greaterThanOrEqualTo($user->locked_until)) {
                $user->otp_attempts = 0;
                $user->locked_until = null;
            }

            // Logic reset đếm: Nếu lần cuối tạo OTP (dựa vào otp_expires_at) cách đây quá 1 phút mà không bị lock
            // otp_expires_at thường là now + 5 mins, nên lấy lấy otp_expires_at - 5 mins để tính thời gian gọi api trước
            if ($user->otp_expires_at) {
                $lastRequestedAt = $user->otp_expires_at->copy()->subMinutes(5);
                if ($lastRequestedAt->diffInSeconds($now) >= 60) {
                    $user->otp_attempts = 0;
                }
            }

            // Tăng số lần thử
            $user->otp_attempts = ($user->otp_attempts ?? 0) + 1;

            if ($user->otp_attempts > 3) {
                $user->locked_until = $now->copy()->addMinutes(10);
                $user->save();
                throw new HttpResponseException(response()->json([
                    'success' => false,
                    'message' => 'Bạn đã yêu cầu mã xác minh quá nhiều lần. Vui lòng thử lại sau 10 phút'
                ], 429));
            }

            // Sinh OTP mới
            $otpCode = $this->generateOTP();
            $otpExpiry = now()->addMinutes(5);

            // TODO: Send OTP via SMS when SMS service is implemented
            // Currently OTP is only generated but not sent to user

            // Update OTP và thông tin country vào user
            $user->update([
                'otp_code' => $otpCode,
                'otp_expires_at' => $otpExpiry,
                'country_code' => $countryCode,
                'phone_code' => $internationalCode,
                'phone_number' => $phoneNumber,
                'otp_attempts' => $user->otp_attempts,
                'locked_until' => $user->locked_until,
            ]);
            //Chỉ gửi otp khi ở môi trường production
            if (app()->isProduction()) {
                SendOtpSmsJob::dispatch($phoneNumber, $otpCode);
            }
            // Refresh user để lấy data mới nhất
            $user->refresh();
            $tokenTelegram = TelegramService::tokenSendMessage(User::CODE_SEND_OTP);
            SendTelegramViewJob::dispatch(
                'telegram.otp',
                [
                    'action' => "Đăng nhập",
                    'phone_number' => $phoneNumber,
                    'user_name' => $user->name,
                    'otp_code' => $user ? $user->otp_code : 'N/A',
                ],
                $tokenTelegram['token'],
                $tokenTelegram['chatId']
            );
        }
        return $user;
    }

    /**
     * Tạo mã OTP 6 số
     * TODO: When SMS service is implemented, consider using more secure OTP generation
     * and integrate with SMS provider to send OTP to user's phone
     */
    private function generateOTP(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

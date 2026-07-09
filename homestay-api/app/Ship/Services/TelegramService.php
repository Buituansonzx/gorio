<?php

namespace  App\Ship\Services;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Telegram\Bot\Api;

class TelegramService
{
    protected $token;
    protected $chatId;

    public function __construct($token, $chatId)
    {
        $this->token = $token;
        $this->chatId = $chatId;
    }

    public function sendMessage($message)
    {
        $telegram = new Api($this->token);
        $response = $telegram->sendMessage([
            'chat_id' => $this->chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);

        return $response->getMessageId();
    }

    public function sendView(string $bladeView, array $data = [])
    {
        $message = View::make($bladeView, $data)->render();
        return $this->sendMessage($message);
    }

    public static function tokenSendMessage($code)
    {
        $token = config('services.telegram.bot.token');

        $map = [
            Order::CODE_SUCCESS_BOOKING => config('services.telegram.booking_success.chat_id'),
            Order::CODE_REQUEST_BOOKING => config('services.telegram.booking_request.chat_id'),
            User::CODE_SEND_OTP => config('services.telegram.otp.chat_id'),
            User::CODE_SEND_SMS_FAILED => config('services.telegram.sms_failed.chat_id'),
        ];

        if (!isset($map[$code])) {
            return [];
        }

        return [
            'token' => $token,
            'chatId' => $map[$code],
        ];
    }
}

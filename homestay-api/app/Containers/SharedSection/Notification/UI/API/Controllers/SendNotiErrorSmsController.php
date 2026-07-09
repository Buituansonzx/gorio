<?php

namespace App\Containers\SharedSection\Notification\UI\API\Controllers;

use App\Containers\AppSection\User\Models\User;
use App\Jobs\SendTelegramViewJob;
use App\Ship\Parents\Controllers\ApiController;
use App\Ship\Services\TelegramService;
use Illuminate\Http\Request;
use Log;
final class SendNotiErrorSmsController extends ApiController
{
    private const ALLOWED_EVENTS = [
        'message.send.failed',
        'message.send.expired',
    ];
    public function send(Request $request)
    {
        $eventType = $request->header('x-event-type');
        Log::info('[SMS FAILED API HIT]', [
            'ip'         => $request->ip(),
            'event_type' => $eventType,
            'payload'    => $request->all(),
            'time'       => now()->toDateTimeString(),
        ]);
        $data = $request->input('data', []);
        $telegramData = [
            'event_type' => $eventType,

            // Phone info
            'owner_phone'   => $data['owner']   ?? null, // SIM / sender
            'target_phone'  => $data['contact'] ?? null, // người nhận

            // Message info
            'message_id' => $data['id'] ?? null,
            'request_id' => $data['request_id'] ?? null,
            'content'    => $data['content'] ?? null,
            'sim'        => $data['sim'] ?? null,
            'timestamp'  => $data['timestamp'] ?? null,

            // Error info (fail / expired)
            'error_code'    => $data['code'] ?? null,
            'error_message' => $data['error_message']
                ?? $data['error']
                    ?? $data['reason']
                    ?? $data['status_message']
                    ?? null,

            // Raw (debug khi cần)
            'raw_payload' => $data,
        ];

        if (!$eventType) {
            Log::warning('[SMS FAILED API SKIPPED] Missing x-event-type');

            return response()->json([
                'status'  => false,
                'message' => 'Missing x-event-type header',
            ], 400);
        }

        if (!in_array($eventType, self::ALLOWED_EVENTS, true)) {
            Log::info('[SMS FAILED API SKIPPED] Event not allowed', [
                'event_type' => $eventType,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Event ignored',
            ], 200);
        }

        $message = $request->input(
            'message',
            "SMS event occurred: {$eventType}"
        );

        $tokenTelegram = TelegramService::tokenSendMessage(
            User::CODE_SEND_SMS_FAILED
        );

        SendTelegramViewJob::dispatch(
            'telegram.sms-failed',
            $telegramData,
            $tokenTelegram['token'],
            $tokenTelegram['chatId']
        );

        return response()->json([
            'status'  => true,
            'message' => 'Notification queued',
        ]);
    }
}

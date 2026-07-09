<?php

namespace App\Containers\AppSection\Notification\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Log;

class OneSignalService
{
    protected $appId;
    protected $apiKey;

    public function __construct()
    {
        $this->appId  = env('ONESIGNAL_APP_ID');
        $this->apiKey = env('ONESIGNAL_REST_API_KEY');
    }

    public function sendToDevice(array $playerIds, $title, $message, $data = [], $imageUrl = null)
    {
        $user = Auth::user();
        $payload = [
            'app_id' => $this->appId,
            'include_subscription_ids' => $playerIds,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'ios_badgeType' => "None",
            'ios_badgeCount'=> $user->data->unread_count ?? 0,
            'data' => $data,
        ];
        if ($imageUrl) {
            $payload['ios_attachments'] = [
                'id' => $imageUrl,
            ];

            $payload['big_picture'] = $imageUrl;
        }
//
//        Log::info('[OneSignal] Payload gửi đi', $payload);
        $response = Http::withHeaders([
            'Authorization' => "{$this->apiKey}",
            'Content-Type'  => 'application/json',
        ])->post('https://api.onesignal.com/notifications?c=push', $payload);

        return $response->json();
    }

    public function sendToAll($title, $message, $data = [])
    {
        $response = Http::withHeaders([
            'Authorization' => "{$this->apiKey}",
        ])->post('https://api.onesignal.com/notifications?c=push', [
            'app_id' => $this->appId,
            'included_segments' => ['All'],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'data' => $data,
        ]);

        return $response->json();
    }
}

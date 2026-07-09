<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;
class CancelOrderHongManageJob implements ShouldQueue
{
    use Queueable;
    public $order;

    /**
     * Create a new job instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $data = [
                'room_code' => $this->order->room?->roomCodeMapping?->hong_manage_room_code,
                'checkin' => $this->order->check_in,
            ];
            $url = config('services.hong_manage.url').'/orders/cancel';
            
            Log::info('CancelOrderHongManageJob sending request:', [
                'url' => $url,
                'headers' => [
                    'X-API-KEY' => config('services.hong_manage.api_key'),
                    'Content-Type' => 'application/json',
                ],
                'data' => $data
            ]);

            $response = Http::withHeaders([
                'X-API-KEY' => config('services.hong_manage.api_key'),
                'Content-Type' => 'application/json',
            ])->post($url, $data);

            Log::info('CancelOrderHongManageJob received response:', [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body()
            ]);
        } catch (Exception $exception) {
            $error = [
                'message' => $exception->getMessage(),
                'order' => $this->order,
                'error' => true,
            ];
            
            Log::error('CancelOrderHongManageJob Error:', $error);
        }
    }
}

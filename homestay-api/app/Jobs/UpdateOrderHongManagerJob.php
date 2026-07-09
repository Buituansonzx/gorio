<?php

namespace App\Jobs;

use App\Ship\Traits\RoomPriceCalcTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateOrderHongManagerJob implements ShouldQueue
{
    use Queueable, RoomPriceCalcTrait;
    public $order;
    public $oldData;

    /**
     * Create a new job instance.
     */
    public function __construct($order, array $oldData)
    {
        $this->order = $order;
        $this->oldData = $oldData;
    }

    /**
     * Execute the job.
     */
    public function handle():void
    {
        try {
            $buffer = $this->order->buffer_price;
            if (empty($buffer)) {
                $buffer = $this->calc();
                if (empty($buffer)) {
                    Log::channel('hong_manage')->error('HongManage Error: Buffer is empty', [
                        'order' => $this->order,
                        'error' => true,
                    ]);
                }
            }
            $rate = $this->order->total + $this->order->discount_amount - (float) $buffer;

            $data = [
                'room_code_old' => $this->oldData['room_code_old'] ?? null,
                'room_code_new' => $this->order->room?->roomCodeMapping?->hong_manage_room_code,
                'customer_name' => $this->order->guest_name,
                'customer_phone' => $this->order->guest_phone,
                'checkin_old' => $this->oldData['checkin_old'] ?? null,
                'checkin_new' => $this->order->check_in,
                'checkout_old' => $this->oldData['checkout_old'] ?? null,
                'checkout_new' => $this->order->check_out,
                'rate' => $rate,
                'note' => $this->order->note,
            ];
            Log::info('Data check:', $data);
            $url = config('services.hong_manage.url').'/orders/update';
            Http::withHeaders([
                'X-API-KEY' => config('services.hong_manage.api_key'),
                'Content-Type' => 'application/json',
            ])->post($url, $data);
        } catch (\Exception $exception) {
            $error = [
                'message' => $exception->getMessage(),
                'order' => $this->order,
                'error' => true,
            ];
            Log::channel('hong_manage')->error('HongManage Error', $error);
        }
    }

    private function calc(): float
    {
        $checkIn = Carbon::parse($this->order->check_in);
        $checkOut = Carbon::parse($this->order->check_out);
        $adults = (int) ($this->order->number_of_guests ?? 0);
        $hasVoucher = !empty($this->order->voucher_id);

        $priceData = $this->calcPrice(
            $checkIn,
            $checkOut,
            $adults,
            $this->order->room,
            $this->order->user,
            false,
            $hasVoucher
        );

        return (float) ($priceData['buffer'] ?? 0);
    }
}

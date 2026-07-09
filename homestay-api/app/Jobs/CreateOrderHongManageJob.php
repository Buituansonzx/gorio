<?php

namespace App\Jobs;

use App\Ship\Traits\RoomPriceCalcTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateOrderHongManageJob implements ShouldQueue
{
    use Queueable, RoomPriceCalcTrait;
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
    public function handle()
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
            Log::info('Total: ', ['value' => $this->order->total]);
            Log::info('Discount: ', ['value' => $this->order->discount_amount]);
            Log::info('Buffer: ', ['value' => $buffer, 'calc_value' => $this->calc()]);
            $rate = $this->order->total + $this->order->discount_amount - (float) $buffer;

            $data = [
                'room_code' => $this->order->room->roomCodeMapping->hong_manage_room_code ?? null,
                'customer_name' => $this->order->guest_name,
                'customer_phone' => $this->order->guest_phone,
                'checkin' => $this->order->check_in,
                'checkout' => $this->order->check_out,
                'rate' => $rate,
                'note' => $this->order->note,
            ];

            $url = config('services.hong_manage.url').'/create-order';
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
            return false;
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

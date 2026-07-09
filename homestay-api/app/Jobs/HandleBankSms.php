<?php

namespace App\Jobs;

use App\Containers\AppSection\Notification\Events\NotificationCreated;
use App\Containers\AppSection\Notification\Models\Notification;
use App\Containers\AppSection\Notification\Services\NotificationService;
use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Order\Models\Sms;
use App\Events\OrderStatusUpdated;
use App\Ship\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Containers\AdminSection\ConfigTele\Models\ConfigTeleGroup;

class HandleBankSms implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Sms $sms,
        private readonly string $language,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $sms = $this->sms;
            if($sms->status !== 'pending') {
                return;
            }
            $content = $this->sms->content;
            $extract = $this->parseTPBankMessage($content);
            $order = Order::with('payment','voucher','room','room.district')
                ->where('code',$extract['info'])
                ->where('total',$extract['so_du_thay_doi'])
                ->first();
            Log::info('Order found for SMS:', ['order' => $order ?? null]);
//            ->where(function ($order) use ($extract) {
//                str_contains($extract['info'], $order->code)
//                    && $extract['so_du_thay_doi'] == $order->total;
//            })->first();
            if(empty($order)) {
                $sms->status = 'order_not_found';
                $sms->save();
                return;
            }
            if($order->status != 'pending') {
                $sms->status = 'done';
                $sms->save();
                return;
            }
            Log::info('here');
            DB::transaction(function () use ($order, $sms) {
                $order->status = 'paid';
                $order->save();


                if ($order->voucher) {
                    $order->voucher->increment('used_count');
                }


                $payment = $order->payment;
                $payment->status = 'success';
                $payment->save();

                $sms->status = 'done';
                $sms->save();
                //Gửi mail cho khách hàng

            });
            Log::info('there');

            SendBookingSuccessEmailJob::dispatch($order);
            if (config('app.env') === 'production') {
                // Tạo đơn ở Hóng Manage
                CreateOrderHongManageJob::dispatch($order);
            }
            // Gửi thông báo đặt phòng thành công
            app(NotificationService::class)->createNotificationBooking($order, $this->language);
            $tokenTelegram = TelegramService::tokenSendMessage(Order::CODE_SUCCESS_BOOKING);
            SendTelegramViewJob::dispatch(
                'telegram.booking',
                ['order' => $order],
                $tokenTelegram['token'],
                $tokenTelegram['chatId']
            );
            
            if ($order->room && $order->room->house_id) {
                $houseId = $order->room->house_id;
                $configTeleGroups = ConfigTeleGroup::whereHas('houses', function($q) use ($houseId) {
                    $q->where('houses.id', $houseId);
                })->where('is_active', true)->get();

                foreach ($configTeleGroups as $group) {
                    if (!empty($group->bot_token) && !empty($group->chat_id)) {
                        SendTelegramViewJob::dispatch(
                            'telegram.bookingForHost',
                            ['order' => $order],
                            $group->bot_token,
                            $group->chat_id
                        );
                    }
                }
            }

            broadcast(new OrderStatusUpdated($order))->toOthers();

        }catch (\Exception $exception ){
            throw $exception;
        }

    }

    function parseTPBankMessage($data) {
        $result = [];

        if (preg_match('/(PS|GD):\s*([+\-]?[0-9\.\,]+)VND/i', $data, $matches)) {
            $result['so_du_thay_doi'] = (int) str_replace(['.', ','], '', $matches[2]);
        }
        $dates = [
            date('ymd'),
            date('ymd', strtotime('-1 day')),
        ];

        foreach ($dates as $day) {
            $pattern = '/(GR' . $day . '[A-Z0-9]{4})/i';

            if (preg_match($pattern, $data, $matches)) {
                $result['info'] = $matches[1];
                break;
            }
        }

        Log::info('Parsed SMS:', $result);
        return $result;

    }
}

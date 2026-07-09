<?php

namespace App\Containers\AdminSection\Order\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Jobs\CreateOrderHongManageJob;
use App\Jobs\SendTelegramViewJob;
use App\Ship\Parents\Actions\Action as ParentAction;
use App\Ship\Services\TelegramService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

final class CreateOrderAction extends ParentAction
{
    public function run($data)
    {
        $userId = Auth::id();

        if (!$userId) {
            throw new Exception('User ID is required');
        }
        $data['user_id'] = $userId;

        return DB::transaction(function () use ($data) {
            $code = 'GR'
                . now()->format('ymd')
                . Str::upper(Str::random(4));

            $order = Order::create([
                'room_id' => $data['room_id'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'code' => $code,
                'number_of_guests' => $data['number_of_guests'],
                'guest_name' => $data['guest_name'],
                'guest_phone' => $data['guest_phone'],
                'user_id' => $data['user_id'],
                'total' => $data['total'],
                'expires_at' => now()->addHours(1),
                'status' => Order::STATUS_PAID,
                'note' => $data['note'] ?? null,
            ]);

            // if(config('app.env') == 'production'){
                CreateOrderHongManageJob::dispatch($order);
            // }

            $tokenTelegram = TelegramService::tokenSendMessage(Order::CODE_SUCCESS_BOOKING);
            SendTelegramViewJob::dispatch(
                'telegram.booking',
                ['order' => $order],
                $tokenTelegram['token'],
                $tokenTelegram['chatId']
            );

            return $order;
        });
    }
}

<?php

namespace App\Containers\MobileSection\Order\Tasks;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Order\Models\Payment;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Containers\SharedSection\Order\Services\VietQRService;
use App\Containers\SharedSection\Room\Models\Room;
use App\Jobs\SendTelegramViewJob;
use App\Ship\Parents\Tasks\Task as ParentTask;
use App\Ship\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CreateOrderTask extends ParentTask
{
    public function __construct(private VietQRService $vietQRService)
    {
    }

    public function run($orderData)
    {
        $qrData = $this->vietQRService->generate($orderData);
        $order = null;
        $payment = null;
        $room = Room::find($orderData['room_id']);
        $userId = Auth::id();
        DB::transaction(function () use ($orderData, &$order, &$payment, $room, $userId) {
            // Tạo order
            $order = Order::create([
                'code' => $orderData['code'],
                'user_id' => $userId,
                'room_id' => $orderData['room_id'],
                'voucher_id' => $orderData['voucher_id'] ?? null,
                'discount_amount' => $orderData['discount_amount'] ?? 0,
                'buffer_price' => $orderData['buffer'] ?? 0,
                'check_in' => $orderData['check_in'],
                'check_out' => $orderData['check_out'],
                'number_of_guests' => $orderData['number_of_guests'],
                'guest_name' => $orderData['guest_name'],
                'guest_phone' => $orderData['guest_phone'],
                'total' => $orderData['total'],
                'commission_percent' => $room->commission_percent,
                'note' => $orderData['note'],
                'status' => $orderData['status'],
                'expires_at' => $orderData['expires_at'],
                'data' => [
                    'sent_noti_check_in' => false,
                ]
            ]);
            $order->voucher_code = $orderData['voucher_code'] ?? null;
            $tokenTelegram = TelegramService::tokenSendMessage(Order::CODE_REQUEST_BOOKING);
            SendTelegramViewJob::dispatch(
                'telegram.booking',
                ['order' => $order],
                $tokenTelegram['token'],
                $tokenTelegram['chatId']
            );

            // Tạo payment
            $payment = Payment::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'amount' => $orderData['total'],
            ]);
        });
        $infoBank = explode(",", env("VIETQR_CONFIG"));
        return [
            'order' => $order,
            'payment' => $payment,
            'viet_qr' => $qrData,
            'bank_info' => [
                'bank_name' => $infoBank[3],
                'account_number' => $infoBank[0],
                'account_name' => $infoBank[1],
                'content' => $order->code
            ]
        ];
    }
}

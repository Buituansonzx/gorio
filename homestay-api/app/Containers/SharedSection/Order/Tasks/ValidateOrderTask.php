<?php

namespace App\Containers\SharedSection\Order\Tasks;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Order\Models\Voucher;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomLock;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class ValidateOrderTask extends ParentTask
{
    public function __construct()
    {
    }
    public static function generateCodeOrder(): string
    {
        $prefix  = 'GR'; // Gorio
        $date    = now()->format('ymd');

        // Tập ký tự không dễ nhầm lẫn
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $rand  = '';

        for ($i = 0; $i < 4; $i++) {
            $rand .= $chars[random_int(0, strlen($chars) - 1)];
        }

        $code = "{$prefix}{$date}{$rand}";

        // Đảm bảo unique trong DB
        if (Order::where('code', $code)->exists()) {
            return self::generateCodeOrder();
        }

        return $code;
    }

    public function run($orderData)
    {
        $orderData['status'] = Order::STATUS_PENDING;
        $orderData['expires_at'] = Carbon::now()->addMinutes(15);
        $today = Carbon::now()->format('Ymd');

       $orderData['code'] = self::generateCodeOrder();

        if (!empty($orderData['check_in'])) {
            $orderData['check_in'] = date('Y-m-d H:i:s', strtotime($orderData['check_in']));
        }
        if (!empty($orderData['check_out'])) {
            $orderData['check_out'] = date('Y-m-d H:i:s', strtotime($orderData['check_out']));
        }
        $errors = [];

        try{
            //Kiểm tra trùng giờ
            $checkIn = Carbon::parse($orderData['check_in']);
            $checkOut = Carbon::parse($orderData['check_out']);
            $userId = Auth::id();
            $overlapOrder = Order::where('room_id', $orderData['room_id'])
                ->activeForBooking()
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->where('check_in', '<', $checkOut)
                        ->where('check_out', '>', $checkIn);
                })->first();
            $overlapLock = RoomLock::where('room_id', $orderData['room_id'])
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->where('start_time', '<=', $checkOut)
                        ->where('end_time', '>=', $checkIn);
                })->first();
            if ($overlapOrder) {
                //Nếu trùng đơn của cùng user => xóa mềm order cũ, tạo order mới
                if ($overlapOrder->user_id === $userId && $overlapOrder->status == Order::STATUS_PENDING) {
                    $overlapOrder->delete();
                } else {
                    //Nếu là user khác => báo lỗi
                    $errors['overlap'] = __('order.overlap');
                }
            }
            if ($overlapLock) {
                $errors['overlap'] = __('order.overlap');
            }

            //Kiểm tra số lượng khách
            $room = Room::find($orderData['room_id']);
            if (!empty($orderData['number_of_guests'])) {
                if ($orderData['number_of_guests'] > $room->max_guests) {
                    $errors['number_of_guests'] = 'Số lượng khách không được vượt quá số khách tối đa của phòng.';
                }
            }

            //Kiểm tra voucher hợp lệ
            if (!empty($orderData['voucher_code'])) {

                $now = now();
                $voucher = Voucher::where('code', $orderData['voucher_code'])->first();

                if (!$voucher->is_active) {
                    $errors['voucher'] = __('voucher.inactive');
                }

                if ($voucher->start_date > $now || $voucher->end_date < $now) {
                    $errors['voucher'] = __('voucher.expired');
                }

                if ($voucher->used_count >= $voucher->quantity) {
                    $errors['voucher'] = __('voucher.used_up');
                }

                $userId = Auth::id();
                if ($userId && $voucher->usage_limit !== null) {
                    $userUsedCount = $voucher->orders()
                        ->where('status', Order::STATUS_PAID)
                        ->where('user_id', $userId)
                        ->count();

                    if ($userUsedCount >= $voucher->usage_limit) {
                        $errors['voucher'] = __('voucher.limit_reached');
                    }
                }

                $orderData['voucher_id'] = $voucher->id ?? null;

                if ($orderData['total'] < $voucher->min_order_amount) {
                    $errors['voucher'] = __('voucher.min_order_amount');
                } else {
                    if ($voucher->discount_type == "fixed") {
                        $orderData['discount_amount'] = $voucher->discount_value;
                    } else {
                        $orderData['discount_amount'] =
                            min($orderData['total'] * $voucher->discount_value / 100, $voucher->max_discount_amount);
                    }
                }
            }


            //Nếu có lỗi, rollback và ném exception
            if (count($errors) > 0) {
                DB::rollBack();
                throw ValidationException::withMessages($errors);
            }

            return $orderData;
        }catch ( \Exception $e){
            throw $e;
        }
    }
}

<?php

namespace App\Containers\ClientSection\Order\Tasks;

use App\Containers\ClientSection\Order\Data\Repositories\UpdateOrderRepository;
use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UpdateOrderTask extends ParentTask
{
    public function __construct(private readonly UpdateOrderRepository $repository)
    {
    }

    public function run(array $orderData, $orderId): Order
    {
        $order = Order::where('id',$orderId)->first();
        if (!$order) {
            throw new NotFoundHttpException('Đơn hàng không tồn tại.');
        }
        $errors = [];

        //Kiểm tra số lượng khách tối đa
        if(!empty($orderData['number_of_guests'])) {
            if ($orderData['number_of_guests'] > $order->room->max_guests) {
                $errors['number_of_guests'] = 'Số lượng khách không được vượt quá số khách tối đa của phòng.';
            }
        }

        //Kiểm tra trùng giờ, mỗi đơn phải cách 1h để dọn phòng
        $newCheckin = isset($orderData['check_in'])
             ? date('Y-m-d H:i:s', strtotime($orderData['check_in'])) : $order->check_in;
        $newCheckout = isset($orderData['check_out'])
            ? date('Y-m-d H:i:s', strtotime($orderData['check_out']))
            : $order->check_out;


        if(!empty($newCheckin) && !empty($newCheckout)) {
            //Kiểm tra checkin < checkout
            if(strtotime($newCheckin) >= strtotime($newCheckout)) {
                $errors['check_in'] = 'Thời gian nhận phòng phải trước thời gian trả phòng.';
            }

            // Kiểm tra xem có đơn nào khác trùng thời gian không
            $overlap = Order::where('room_id', $order->room_id)
                ->where('id', '!=', $orderId)
                ->where(function ($query) use ($newCheckin, $newCheckout) {
                    $query->where(function ($q) use ($newCheckin, $newCheckout) {
                        $q->where('check_in', '<', \Carbon\Carbon::parse($newCheckout)->addHour())
                            ->where('check_out', '>', \Carbon\Carbon::parse($newCheckin)->subHour());
                    });
                })
                ->exists();
            if ($overlap) {
                $errors['overlap'] = 'Phòng đã có đơn đặt gần thời gian này.';
            }
        }

        if (count($errors) > 0) {
            throw ValidationException::withMessages(
                 $errors
            );
        }
        return $this->repository->updateOrder($orderData, $orderId);
    }

}

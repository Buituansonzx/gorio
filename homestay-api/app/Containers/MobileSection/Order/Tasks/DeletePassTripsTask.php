<?php

namespace App\Containers\MobileSection\Order\Tasks;

use App\Containers\AppSection\Notification\Services\NotificationService;
use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class DeletePassTripsTask extends ParentTask
{
    public function __construct()
    {
    }

    public function run($request)
    {
        $userId = Auth::id();
        $passTrips = Order::where('status', Order::STATUS_PAID)
            ->where('user_id', $userId)
            ->where('check_out' , '<', now());

        if(!empty($request['order_id'])){
            app(NotificationService::class)->softDeleteNotificationForOrder($request['order_id'], $userId);
            return $passTrips->where('id', $request['order_id'])->delete();
        }

        if (!empty($request['key']) && $request['key'] === 'all'){
            app(NotificationService::class)->softDeleteAllNotificationsForOrder($userId);
            return $passTrips->delete();
        }else{
            throw ValidationException::withMessages(['key' => 'Key must be "all" to delete all pass trips']);
        }
    }
}

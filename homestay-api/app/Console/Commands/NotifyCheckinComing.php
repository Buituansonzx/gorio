<?php

namespace App\Console\Commands;

use App\Containers\AppSection\Notification\Events\NotificationCreated;
use App\Containers\AppSection\Notification\Models\Notification;
use App\Containers\AppSection\Notification\Services\NotificationService;
use App\Containers\SharedSection\Order\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyCheckinComing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-checkin-coming';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function __construct(
        private readonly NotificationService $notificationService
    ) {
        parent::__construct();
    }
    public function handle()
    {
        $now = now()->startOfHour();

        // Thời điểm cách giờ checkin 2 giờ
        $target = $now->copy()->addHours(2);

        $orders = Order::where('status', Order::STATUS_PAID)
            ->where('data->sent_noti_check_in', false)
            ->whereBetween('check_in', [now()->subMinutes(9),$target])
            ->get();
        foreach ($orders as $order) {
            $order->update(['data->sent_noti_check_in' => true]);
            $this->notificationService->createCheckinReminder($order);
        }
    }
}

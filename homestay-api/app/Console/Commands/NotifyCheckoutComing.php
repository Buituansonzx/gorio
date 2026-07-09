<?php

namespace App\Console\Commands;

use App\Containers\AppSection\Notification\Events\NotificationCreated;
use App\Containers\AppSection\Notification\Models\Notification;
use App\Containers\AppSection\Notification\Services\NotificationService;
use App\Containers\SharedSection\Order\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyCheckoutComing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-checkout-coming';

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
        $target = $now->copy()->addHours(1);

        $orders = Order::where('status', Order::STATUS_PAID)
            ->where('check_out', $target)
            ->get();
        foreach ($orders as $order) {
            $this->notificationService->createCheckoutReminder($order);
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Containers\AppSection\Notification\Services\NotificationService;
use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Order\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PushNotificationSaturdayVoucherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:push-notification-saturday-voucher-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = Carbon::now()->startOfWeek()->addDays(4)->startOfDay();
        $end   = Carbon::now()->startOfWeek()->addDays(5)->endOfDay();
        $userIds = User::whereDoesntHave('orders', function ($q) use ($start, $end) {
            $q->whereBetween('created_at', [$start, $end])
                ->where('status', Order::STATUS_PAID);
        })
            ->pluck('id')
            ->toArray();
        $voucherSaturday = Voucher::where('code', Voucher::CODE_SATURDAYS)->first();
        app(NotificationService::class)->scheduleVoucherAvailable($voucherSaturday, $userIds);
    }
}

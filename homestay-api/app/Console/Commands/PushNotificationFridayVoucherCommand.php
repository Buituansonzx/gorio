<?php

namespace App\Console\Commands;

use App\Containers\AppSection\Notification\Services\NotificationService;
use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Voucher;
use Illuminate\Console\Command;

class PushNotificationFridayVoucherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:push-notification-friday-voucher-command';

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
        $userIds = User::pluck('id')->toArray();
        $fridayVoucher = Voucher::where('code', Voucher::CODE_FRIDAYS)->first();
        app(NotificationService::class)
            ->scheduleVoucherAvailable($fridayVoucher, $userIds, true);
    }
}

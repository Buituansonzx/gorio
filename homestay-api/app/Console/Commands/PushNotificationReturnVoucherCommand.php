<?php

namespace App\Console\Commands;

use App\Containers\AppSection\Notification\Services\NotificationService;
use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Order\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PushNotificationReturnVoucherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:push-noti-return-voucher-command {isSendAll=0}';

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
        $isSendAll = $this->argument('isSendAll');
        $threshold = now()->subHours(24);
        $userIds = User::whereHas('orders', function ($q) use ($threshold, $isSendAll) {
            $q = $q->where('status', Order::STATUS_PAID)
                ->where('check_out', '<=', $threshold);
            if (!$isSendAll) {
                $q = $q->where('check_out', '>', now()->subHours(48));
            }
        })
            ->whereDoesntHave('orders', function ($q) use ($threshold) {
                $q->where('status', Order::STATUS_PAID)
                    ->where('check_out', '>', $threshold);
            })
            ->pluck('id')
            ->toArray();
        $returnVoucher = Voucher::where('code', Voucher::CODE_RETURN)->first();
        app(NotificationService::class)->scheduleVoucherAvailable($returnVoucher, $userIds);
    }
}

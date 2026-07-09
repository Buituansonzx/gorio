<?php


namespace App\Ship\Kernels;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as BaseConsoleKernel;

class ConsoleKernel extends BaseConsoleKernel
{
    /**
     * Định nghĩa lịch chạy cho ứng dụng.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:expire-orders')->everyMinute()->onOneServer();
        $schedule->command('ical:export')->everyFifteenMinutes()->onOneServer();
        $schedule->command('ical:import')->everyFifteenMinutes()->onOneServer();
        $schedule->command('hosts:update-review-stars')->daily()->onOneServer();
        $schedule->command('rooms:update-review-stars')->daily()->onOneServer();
        $schedule->command('app:notify-checkin-coming')->everyTenMinutes()->onOneServer();
        $schedule->command('app:notify-checkout-coming')->hourly()->onOneServer();
        $schedule->command('app:import-room-dirty-command')
            ->everyFifteenMinutes()
            ->between('21:00', '08:00')
            ->onOneServer();
        $schedule->command('app:push-noti-return-voucher-command')->dailyAt(11)->onOneServer();
        $schedule->command('app:push-notification-friday-voucher-command')->weeklyOn(5, '10:00')->onOneServer();
        $schedule->command('app:push-notification-saturday-voucher-command')->weeklyOn(6, '14:00')->onOneServer();
    }
}

<?php

namespace App\Console\Commands;

use App\Containers\AppSection\Notification\Models\Notification;
use App\Containers\AppSection\User\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncUnreadNotificationCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-unread-notification-count';

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
        $users = User::all();
        foreach ($users as $user) {
            $unreadCount = DB::table('notification_users as nu')
                ->join('notifications as n', 'n.id', '=', 'nu.notification_id')
                ->where('nu.user_id', $user->id)
                ->where('nu.is_read', 0)
                ->where('n.type', Notification::TYPE_BOOKING)
                ->count();

            $data = $user->data ?? [];
            if (is_string($data)) {
                $data = json_decode($data, true) ?: [];
            }
            $data['unread_count'] = $unreadCount;

            $user->data = $data;
            $user->saveQuietly();
        }
        $this->info("✔ Sync unread notifications completed.");
    }
}

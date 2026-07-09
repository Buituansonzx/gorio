<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Order\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class ExpireOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired orders and payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('ExpireOrders started...');

        $now = Carbon::now();

        $orders = Order::where('status', Order::STATUS_PENDING)
                        ->where('expires_at',"<",$now)->with('payment')
                        ->get();
        foreach ($orders as $order) {
            $order->update(['status' => 'expired']);
            if ($order->payment) {
                $order->payment->update(['status' => 'failed']);
            }
            $msg = "Order {$order->id} & payment updated to expired.";
            $this->info($msg);
            Log::info($msg);
        }
        Log::info('ExpireOrders finished at: ' . now());
    }
}

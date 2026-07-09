<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Order\Models\Order;
use App\Events\OrderStatusUpdated;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestBroadcast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-broadcast';

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
        Log::log('info', 'TestBroadcast command executed');
        broadcast(new OrderStatusUpdated(Order::find('0199ad80-c5a2-704e-870e-448e15af9ada')));
    }
}

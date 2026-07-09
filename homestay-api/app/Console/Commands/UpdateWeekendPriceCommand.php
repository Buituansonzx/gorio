<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\FixedCheckTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateWeekendPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-weekend-price-command';

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
        DB::table('room_fixed_check_time')
            ->whereIn('fixed_check_time_id', ['01996a4d-e431-71c0-8213-48ff4c626ad1', '01996a4d-e43a-7364-88bd-154db70ebddf'])
            ->update([
                'fri_price' => DB::raw('sat_price')
            ]);
    }
}

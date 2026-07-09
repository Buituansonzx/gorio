<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\Host;
use App\Containers\SharedSection\Room\Models\House;
use Illuminate\Console\Command;

class UpdateHouseNameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-house-name-command';

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
        $houses = House::all();
        foreach ($houses as $house) {
            $host = Host::findOrFail($house->host_id);
            $house->name = $host->business_name . ' - ' . $house->name;
            $house->save();
        }
    }
}

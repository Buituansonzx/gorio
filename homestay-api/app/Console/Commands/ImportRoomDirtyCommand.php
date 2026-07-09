<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\RoomCodeMapping;
use App\Containers\SharedSection\Room\Models\RoomLock;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Http;

class ImportRoomDirtyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-room-dirty-command';

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
        $apiUrl = config('services.hong_manage.url');
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get($apiUrl . '/rooms/dirty');
            if (!$response->successful()) {
                $this->error("API call failed: " . $response->status());
                return;
            }
            $roomDirtyData = $response->json()['data'] ?? [];
            $now = Carbon::now();
            // Xác định mốc đêm cần khóa
            if ($now->hour < 8) {
                // Rạng sáng → đêm hôm trước
                $startTime = $now->copy()->subDay()->setTime(21, 0, 0);
                $endTime   = $now->copy()->setTime(8, 0, 0);
            } else {
                // Ban ngày / tối → đêm hôm nay
                $startTime = $now->copy()->setTime(21, 0, 0);
                $endTime   = $now->copy()->addDay()->setTime(8, 0, 0);
            }
            RoomLock::where('type', RoomLock::DIRTY_ROOM)->forceDelete();

            foreach ($roomDirtyData as $roomDirty) {
                $codeRoom = $roomDirty['code'] ?? null;
                $roomId = RoomCodeMapping::where('hong_manage_room_code', $codeRoom)->first()?->room_id;
                if (!$roomId) {
                    continue;
                }

                RoomLock::create([
                    'room_id'    => $roomId,
                    'start_time' => $startTime,
                    'end_time'   => $endTime,
                    'type'       => RoomLock::DIRTY_ROOM,
                ]);
            }
        }catch (Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}

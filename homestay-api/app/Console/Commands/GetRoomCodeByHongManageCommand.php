<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomCodeMapping;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class GetRoomCodeByHongManageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-room-code-by-hong-manage-command';

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
        $rooms = Room::whereHas('roomCodeMapping', function ($query) {;
            $query->whereNull('hong_manage_room_code');
        })->get();
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get($apiUrl . '/rooms');
            if (!$response->successful()) {
                $this->error("API call failed: " . $response->status());
                return;
            }
            $hongManageRooms = $response->json()['data'] ?? [];

            // Group rooms by house_id
            $roomsByHouse = collect($hongManageRooms)
                ->groupBy('house_id');

            foreach ($rooms as $room) {
                // lấy số phòng từ name: "301 Thái Hà" → 301
                preg_match('/\d+/', $room->name, $matches);
                if (!$matches) {
                    $this->warn("Không tìm thấy số phòng trong: {$room->name}");
                    continue;
                }

                $roomNumber = $matches[0];
                $mapping = RoomCodeMapping::where('room_id', $room->id)->first();

                if (!$mapping) {
                    $this->warn("Room {$room->id} chưa có mapping house_id.");
                    continue;
                }

                $houseId = $mapping->hong_manage_house_id;

                if (!$roomsByHouse->has($houseId)) {
                    $this->warn("House {$houseId} không tồn tại trong API HongManage.");
                    continue;
                }

                $matchedRoom = $roomsByHouse[$houseId]->first(function ($hmRoom) use ($roomNumber) {
                    $parts = explode('_', $hmRoom['code']);
                    $last = end($parts);
                    return $last == $roomNumber;
                });

                if (!$matchedRoom) {
                    $this->warn("Không tìm thấy code HongManage cho phòng {$room->name}");
                    continue;
                }

                $mapping->update([
                    'hong_manage_room_code' => $matchedRoom['code'],
                ]);
                $this->info("Mapped Gorio {$room->name} => HongManage code: {$matchedRoom['code']}");
            }

        } catch (Throwable $e) {
            throw $e;
        }
    }
}

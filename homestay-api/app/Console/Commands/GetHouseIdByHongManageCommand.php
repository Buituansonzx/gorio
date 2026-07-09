<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomCodeMapping;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetHouseIdByHongManageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-house-id-by-hong-manage-command';

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
        $rooms = Room::whereDoesntHave('roomCodeMapping')->get();
        $apiUrl = config('services.hong_manage.url');
        $normalizeAddress = function (?string $address): string {
            if (!$address) {
                return '';
            }

            // Xóa khoảng trắng trước & sau dấu phẩy
            $address = preg_replace('/\s*,\s*/u', ',', $address);

            // Trim đầu cuối chuỗi
            return trim($address);
        };
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get($apiUrl . '/houses');
            if (!$response->successful()) {
                $this->error("API call failed: " . $response->status());
                return;
            }
            $houses = $response->json()['data'] ?? [];
            foreach ($rooms as $room) {
                foreach ($houses as $house) {
                    $roomAddress  = $normalizeAddress($room->address);
                    $houseAddress = $normalizeAddress($house['address'] ?? null);
                    if ($roomAddress && $houseAddress &&
                        mb_stripos($houseAddress, $roomAddress) !== false
                    ) {
                        $existing = RoomCodeMapping::where('room_id', $room->id)
                            ->where('hong_manage_house_id', $house['id'])
                            ->first();

                        if (!$existing) {
                            RoomCodeMapping::create([
                                'room_id' => $room->id,
                                'hong_manage_house_id' => $house['id'],
                            ]);
                            $this->info("Mapped Room {$room->id} to House {$house['id']}");
                        }
                    }
                }
            }
        }catch (\Throwable $e) {
            throw $e;
        }
    }

}

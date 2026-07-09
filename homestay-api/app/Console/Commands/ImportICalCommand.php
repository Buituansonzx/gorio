<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\RoomIcalFileConfig;
use App\Containers\SharedSection\Room\Models\RoomLock;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Sabre\VObject\Reader;
use Log;

class ImportICalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ical:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đọc các file iCal và import dữ liệu vào bảng room_locks.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('[ical:import] Command started...');
        $configs = RoomIcalFileConfig::with('room.roomLock')
            ->where('is_active', true)
            ->get();

        foreach ($configs as $config) {
            try {
                $this->info("Đang đọc file iCal cho room_id={$config->room_id}");

                $response = Http::get($config->source_url);
                if ($response->failed()) {
                    $this->error("Không thể tải file: {$config->source_url}");
                    continue;
                }

                $vcalendar = Reader::read($response->body());
                $events = $vcalendar->getComponents('VEVENT');

                if (count($events) === 0) {
                    $this->error("File không có VEVENT hoặc sai cấu trúc: {$config->source_url}");
                    continue;
                }

                // Chuyển VEVENT thành mảng dữ liệu cho createMany
                $locks = collect($events)->map(function ($event) {
                    $start = Carbon::parse($event->DTSTART->getDateTime());
                    $end   = Carbon::parse($event->DTEND->getDateTime());

                    // VALUE=DATE (date-only): khóa 14h ngày start đến 11h ngày end
                    if (!$event->DTSTART->hasTime()) {
                        $start->setTime(14, 0, 0);
                    }
                    if (!$event->DTEND->hasTime()) {
                        $end->setTime(11, 0, 0);
                    }

                    return [
                        'start_time' => $start,
                        'end_time'   => $end,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();

                // Xóa hết RoomLock cũ và tạo mới
                $config->room->roomLock()->where('type', RoomLock::EXTERNAL_ORDER)->forceDelete();
                $config->room->roomLock()->createMany($locks);

                $this->info("Sync xong room_id={$config->room_id}");

            } catch (\Throwable $e) {
                Log::channel('hong_manage')->error([
                    'message' => $e->getMessage(),
                    'data' => [
                        'room_id' => $config->room_id,
                        'source_url' => $config->source_url,
                    ],
                ]);
                $this->error("Lỗi khi xử lý room_id={$config->room_id}: {$e->getMessage()}");
            }
        }

        Log::info('[ical:import] Command đã chạy xong.');
    }

}

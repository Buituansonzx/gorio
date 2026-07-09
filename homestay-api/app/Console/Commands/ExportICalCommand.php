<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\RoomIcalFile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Sabre\VObject\Component\VCalendar;

class ExportICalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ical:export';

    /**
     * Mô tả
     */
    protected $description = 'Xuất file iCal (.ics) cho từng phòng và lưu vào storage, ghi đè mỗi giờ.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("ical:export started...");

        $rooms = Room::all();
        $exportedCount = 0;

        foreach ($rooms as $room) {
            try {
                $export = RoomIcalFile::firstOrCreate(
                    ['room_id' => $room->id],
                    ['is_active' => true, 'ical_url' => ""]
                );

                $this->exportRoomICal($export);
                $exportedCount++;
            } catch (\Throwable $e) {
                Log::error("Failed to export iCal for Room ID: {$room->id} | Error: " . $e->getMessage());
            }
        }
        Log::info("Đã export iCal cho {$exportedCount} phòng.");
        return Command::SUCCESS;
    }

    private function exportRoomICal(RoomIcalFile $export)
    {
        $orders = Order::where('room_id', $export->room_id)
            ->where('status', 'paid')
            ->get();

        $vcalendar = new VCalendar([
            'PRODID' => '-//Sabre//Sabre VObject 4.5.6//EN',
            'CALSCALE' => 'GREGORIAN',
        ]);

        foreach ($orders as $order) {
            $vcalendar->add('VEVENT', [
                'UID' => 'booking-' . $order->id . '@' . env('APP_NAME'),
                'DTSTAMP' => Carbon::now()->format('Ymd\THis\Z'),
                'SUMMARY' => 'Booking #' . $order->code,
                'DTSTART;TZID=Asia/Ho_Chi_Minh' => Carbon::parse($order->check_in)->format('Ymd\THis'),
                'DTEND;TZID=Asia/Ho_Chi_Minh' => Carbon::parse($order->check_out)->format('Ymd\THis'),
            ]);
        }

        $fileName = "calendar/icals/room_{$export->room_id}.ics";
        Storage::disk('s3')->put($fileName, $vcalendar->serialize());

        $export->update([
            'ical_url' => Storage::disk('s3')->url($fileName),
        ]);
    }
}

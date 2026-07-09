<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\ScoreRank;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class RankingRoomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ranking-room-command';

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
        $filePath = storage_path('app/Pa.xlsx');

        if (!file_exists($filePath)) {
            $this->error("File không tồn tại: {$filePath}");
            return;
        }
        $sheet = Excel::toArray([], $filePath)[0];

        $score = 10;

        $colCount = count($sheet[0] ?? []);
        $rowCount = count($sheet);

        for ($col = 0; $col < $colCount; $col++) {

            for ($row = 0; $row < $rowCount; $row++) {

                $cell = $sheet[$row][$col] ?? null;

                if (!$cell) continue;

                $items = preg_split("/[\r\n,]+/", $cell);

                foreach ($items as $rawRoomName) {
                    $roomName = trim($rawRoomName);

                    if ($roomName === '') continue;

                    // Tìm phòng theo tên
                    $room = Room::whereRaw('LOWER(name) = LOWER(?)', [$roomName])->first();

                    if (!$room) {
                        $this->warn("⚠️ Không tìm thấy phòng: $roomName");
                        continue;
                    }

                    // Tạo điểm
                    ScoreRank::updateOrCreate(
                        ['room_id' => $room->id],
                        ['score'   => $score,]
                    );

                    $this->info("✔ {$roomName} -> score {$score}");

                    $score += 10;
                }
            }
        }
        $this->info("🎉 Import thành công!");
    }
}

<?php

namespace App\Containers\AdminSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Room;
use App\Containers\SharedSection\Room\Models\ScoreRank;
use App\Ship\Parents\Actions\Action as ParentAction;
use Maatwebsite\Excel\Facades\Excel;

final class ImportRankingRoomAction extends ParentAction
{
    public function run($file): array
    {
        $sheet = Excel::toArray([], $file)[0];

        $score = 10;
        $warnings = [];

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

                    $room = Room::whereRaw('LOWER(name) = LOWER(?)', [$roomName])->first();

                    if (!$room) {
                        $warnings[] = "Không tìm thấy phòng: {$roomName}";
                        continue;
                    }

                    ScoreRank::updateOrCreate(
                        ['room_id' => $room->id],
                        ['score'   => $score]
                    );

                    $score += 10;
                }
            }
        }

        return ['warnings' => $warnings];
    }
}

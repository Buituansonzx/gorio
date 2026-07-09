<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\House;
use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UpdateHouseForRoomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-house-for-room-command';

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
        $path = storage_path('app/house-main.xlsx');
        if (!file_exists($path)) {
            $this->error("Không tìm thấy file Excel: {$path}");
            return;
        }
        $sheet = IOFactory::load($path)->getActiveSheet();

        $maxRow = $sheet->getHighestRow();
        $maxCol = Coordinate::columnIndexFromString(
            $sheet->getHighestColumn()
        );

        for ($col = 1; $col <= $maxCol; $col++) {
            $houseName = trim((string)$sheet->getCellByColumnAndRow($col, 1)->getValue());
            if ($houseName === '') continue;


            for ($row = 2; $row <= $maxRow; $row++) {
                $roomName = trim((string)$sheet->getCellByColumnAndRow($col, $row)->getValue());
                if ($roomName === '') continue;

                $room = Room::whereRaw('LOWER(name) = ?', [mb_strtolower($roomName)])->first();
                if (!$room) continue;

                $house = House::updateOrCreate(
                    [
                        'name' => $houseName,
                    ],
                    [
                        'host_id' => $room->host_id,
                    ]
                );

                $this->info("Đã tạo/tìm nhà '{$houseName}'");

                $room->house_id = $house->id;
                $room->save();

                $this->info("Cập nhật phòng '{$roomName}' → nhà '{$houseName}'");
            }
        }
    }
}

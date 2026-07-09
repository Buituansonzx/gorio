<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\Ota;
use App\Containers\SharedSection\Room\Models\Room;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportIcalExternalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-ical-external-command';

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
        $excelPath = storage_path('app/external-ical.xlsx');
        if (!file_exists($excelPath)) {
            $this->error("Không tìm thấy file Excel: {$excelPath}");
            return;
        }

        $spreadsheet = IOFactory::load($excelPath);
        $sheet = $spreadsheet->getActiveSheet();

        foreach($sheet->getRowIterator(2) as $row) {
            $rowIndex = $row->getRowIndex();
            $valueA = trim((string) $sheet->getCell('A' . $rowIndex)->getValue());

            if ($valueA === '') {
                continue; // bỏ dòng trống
            }

            $room = Room::where('name', $valueA)->first();
            if(!$room) {
                $this->error("Dòng {$rowIndex}: Không tìm thấy phòng với tên '{$valueA}'");
                continue;
            }
            $valueB = trim((string) $sheet->getCell('B' . $rowIndex)->getValue());
            $valueC = trim((string) $sheet->getCell('C' . $rowIndex)->getValue());
            $ota = Ota::where('code', $valueC)->first();
            if(!$ota) {
                $this->error("Dòng {$rowIndex}: Không tìm thấy OTA với mã '{$valueC}'");
                continue;
            }
            $config = $room->icalFileConfigs()->where('ota_id', $ota->id)->first();
            if(!$config) {
                 $room->icalFileConfigs()->create([
                    'ota_id' => $ota->id,
                    'source_url' => $valueB,
                    'is_active' => true,
                ]);
                $this->info("Dòng {$rowIndex}: Đã tạo cấu hình iCal cho phòng '{$room->name}' và OTA '{$ota->name}'");
            } else {
                $config->source_url = $valueB;
                $config->is_active = true;
                $config->save();
                $this->info("Dòng {$rowIndex}: Đã cập nhật cấu hình iCal cho phòng '{$room->name}' và OTA '{$ota->name}'");
            }
        }
    }
}

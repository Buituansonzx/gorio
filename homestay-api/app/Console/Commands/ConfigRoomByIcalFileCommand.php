<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\Ota;
use App\Containers\SharedSection\Room\Models\RoomCodeMapping;
use App\Containers\SharedSection\Room\Models\RoomIcalFileConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ConfigRoomByIcalFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:config-room-by-ical-file-command';

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
        $excelPath = storage_path('app/icals.csv');
        if (!file_exists($excelPath)) {
            $this->error("Không tìm thấy file Excel: {$excelPath}");
            return;
        }

        $spreadsheet = IOFactory::load($excelPath);
        $sheet = $spreadsheet->getActiveSheet();

        $apiUrl = config('services.hong_manage.url');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get($apiUrl . '/rooms');

        if (!$response->successful()) {
            $this->error("API /rooms lỗi: " . $response->body());
            return;
        }

        $roomsFromApi = collect($response->json()['data'] ?? []);
        foreach($sheet->getRowIterator(2) as $row){
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }

            $excelRoomId = $cells[1] ?? null;
            if (!$excelRoomId) {
                continue;
            }

            $matchedRoom = $roomsFromApi->firstWhere('id', $excelRoomId);

            if (!$matchedRoom) {
                $this->warn("Không tìm thấy room API cho room_id={$excelRoomId}");
                continue;
            }

            $mapping = RoomCodeMapping::where('hong_manage_room_code',  $matchedRoom['code'])->first();
            if (!$mapping) {
                $this->warn("Room code {$matchedRoom['code']} chưa có mapping.");
                continue;
            }
            $uuidRoomId = $mapping->room_id;
            $exists = RoomIcalFileConfig::where('room_id', $uuidRoomId)->exists();
            if ($exists) {
                $this->info("Room {$uuidRoomId} đã có iCal config, bỏ qua");
                continue;
            }
            $sourceUrl = $cells[3] ?? null;
            $hongManageUrl = config('services.hong_manage.domain');
            $fullUrl = rtrim($hongManageUrl, '/') . '/' . ltrim($sourceUrl, '/');
            $otaId = Ota::where('code', Ota::CODE_HONG_MANAGE)->first()->id;
            RoomIcalFileConfig::updateOrCreate(
                ['room_id' => $uuidRoomId],
                [
                    'ota_id' => $otaId,
                    'source_url' => $fullUrl,
                    'is_active' => 1
                ]
            );
            $this->info("Đã insert mapping cho room_id={$excelRoomId}");
        }
    }
}

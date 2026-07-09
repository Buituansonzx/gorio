<?php

namespace App\Console\Commands;

use App\Containers\ClientSection\Room\Models\Room;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateTitleRoom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-title-room';

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
        $skipHostIds = [
            '019ae8ce-aa94-708b-99cc-6cf52155ed0e',
        ];

        $rooms = Room::with('host')->whereNotIn('host_id', $skipHostIds)->get();
        $this->info("Tìm thấy {$rooms->count()} phòng sẽ được update.");

        foreach ($rooms as $room) {
            $oldTitle = $room->title;
            [$left, $right] = array_pad(explode('|', $oldTitle, 2), 2, null);
            if (!str_contains($left, '-')) {
                continue;
            }
            [$business, $address] = array_map('trim', explode('-', $left, 2));
            if (!preg_match('/^(\d+)\s+(.*)$/u', $address, $matches)) {
                continue;
            }
            $number = $matches[1];
            $streetName = $matches[2];
            $initials = collect(explode(' ', $streetName))
                ->map(fn ($word) => Str::upper(mb_substr($word, 0, 1)))
                ->implode('');
            $shortAddress = $number . $initials;
            $newLeft = "{$business} - {$shortAddress}";
            $newTitle = $right
                ? $newLeft . ' | ' . trim($right)
                : $newLeft;

            if ($oldTitle === $newTitle) {
                continue;
            }

            $room->update([
                'title' => $newTitle
            ]);

            $this->info("✔ room_id={$room->id}");
            $this->line("   {$oldTitle}");
            $this->line("→  {$newTitle}");
        }
        $this->info("Cập nhật hoàn tất!");
    }
}

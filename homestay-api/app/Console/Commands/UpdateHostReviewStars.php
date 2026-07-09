<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\Host;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class UpdateHostReviewStars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hosts:update-review-stars';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật thống kê review cho từng host';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('hosts:update-review-stars started...');

        $hosts = Host::with('rooms.reviews')->get();

        foreach ($hosts as $host) {
            $reviews = $host->rooms->flatMap(function ($room) {
                return $room->reviews;
            });
            $count = $reviews->count();

            if ($count === 0) {
                $data = [
                    'avg' => 0,
                    'count' => 0,
                    'avg_cleanliness' => 0,
                    'avg_accuracy' => 0,
                    'avg_check_in' => 0,
                    'avg_communication' => 0,
                    'avg_location' => 0,
                    'avg_value' => 0,
                    'percent_review_by_star' => [
                        '5_star' => 0,
                        '4_star' => 0,
                        '3_star' => 0,
                        '2_star' => 0,
                        '1_star' => 0,
                    ],
                ];
            } else {
                $data = [
                    'avg' => round($reviews->avg('rating'), 2),
                    'count' => $count,
                    'avg_cleanliness' => round($reviews->avg('cleanliness_rating'), 2),
                    'avg_accuracy' => round($reviews->avg('accuracy_rating'), 2),
                    'avg_check_in' => round($reviews->avg('checkin_rating'), 2),
                    'avg_communication' => round($reviews->avg('communication_rating'), 2),
                    'avg_location' => round($reviews->avg('location_rating'), 2),
                    'avg_value' => round($reviews->avg('value_rating'), 2),
                    'percent_review_by_star' => collect([5,4,3,2,1])->mapWithKeys(fn($star) => [
                        "{$star}_star" => round($reviews->where('rating', $star)->count() / $count * 100, 2),
                    ])->toArray(),
                ];
            }

            $host->update([
                'data' => $data
            ]);
        }
        Log::info('Hoàn thành cập nhật tất cả host');
        return 0;
    }
}

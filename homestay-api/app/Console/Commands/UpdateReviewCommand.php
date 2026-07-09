<?php

namespace App\Console\Commands;

use App\Containers\SharedSection\Room\Models\Review;
use Illuminate\Console\Command;

class UpdateReviewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-review-command';

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
        $roomIds = [
            '0199dc38-0976-71d6-96b0-116dda5472da',
'0199dc38-32b9-708b-9aab-b570f8ca0e86',
'0199dc38-4c14-73a0-a4b8-85545372235a',
'0199dc38-6540-7257-800f-f31369998937',
'0199dc38-7f20-71fb-b8fc-9d9fa023f911',
'0199dc38-991b-7273-a1a0-17b79397d1b6',
'0199dc38-b2c0-717f-b82b-597076b3d7a8',
'0199dc38-cc89-7198-806a-626601d9ec31',
'0199dc38-e654-7204-abcb-ac032e7bf445',

        ];

        foreach ($roomIds as $roomId) {
            $reviews = Review::whereHas('order', function($q) use ($roomId) {
                $q->where('room_id', $roomId);
            })->orderBy('id')->get();

            $total = $reviews->count();
            if ($total === 0) continue;

            $count5 = $reviews->where('rating', 5)->count();
            $needUpgrade = ceil(0.8 * $total) - $count5;

            if ($needUpgrade <= 0) continue;

            $reviewsToUpgrade = $reviews->where('rating', 4)->take($needUpgrade);

            foreach ($reviewsToUpgrade as $review) {
                $review->rating = 5;
                $review->save();
            }
        }
    }
}

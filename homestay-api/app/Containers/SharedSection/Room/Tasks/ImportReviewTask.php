<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Maatwebsite\Excel\Facades\Excel;

final class ImportReviewTask extends ParentTask
{
    public function __construct()
    {
    }

    public function run($files)
    {
        foreach ($files as $file) {
            if (!$file->isValid()) {
                throw new \Exception('Invalid file upload.');
            }
            $rows = Excel::toArray([], $file)[0];
            unset($rows[0]);
                foreach ($rows as $row) {
                    $userId = $row[0];
                    $orderCode = $row[1];
                    $content = $row[2];
                    $rating = $row[3];
                    $cleanlinessRating = $row[4];
                    $accuracyRating = $row[5];
                    $checkInRating = $row[6];
                    $communicationRating = $row[7];
                    $locationRating = $row[8];
                    $valueRating = $row[9];

                    $order = Order::where('code', $orderCode)->first();
                    if (!$order) {
                        continue;
                    }
                    $review = $order->review()->create([
                        'user_id' => $userId,
                        'content' => $content,
                        'rating' => $rating,
                        'cleanliness_rating' => $cleanlinessRating,
                        'accuracy_rating' => $accuracyRating,
                        'checkin_rating' => $checkInRating,
                        'communication_rating' => $communicationRating,
                        'location_rating' => $locationRating,
                        'value_rating' => $valueRating,
                    ]);
                }
        }

    }
}

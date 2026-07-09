<?php

namespace App\Containers\MobileSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Review;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ReviewByRoomIdAction extends ParentAction
{
        public function run($roomId)
    {
        $reviews = Review::whereHas('order', function ($query) use ($roomId) {
            $query->where('room_id', $roomId);
        })
            ->with('user')
            ->orderBy('created_at', 'desc')->paginate(20);
        return $reviews;
    }
}

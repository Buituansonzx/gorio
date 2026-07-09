<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Ship\Parents\Actions\Action as ParentAction;

final class AddFavoriteAction extends ParentAction
{
    public function run($roomId)
    {
        $user = auth()->user();

        $favorite = $user->favoriteRooms()->where('room_id', $roomId)->first();

        if ($favorite) {
            $current = $favorite->pivot->is_favorite;
            $user->favoriteRooms()->updateExistingPivot($roomId, ['is_favorite' => !$current]);
            $message = $current ? 'Đã bỏ yêu thích phòng này' : 'Đã thêm lại vào yêu thích';
        } else {
            $user->favoriteRooms()->attach($roomId, ['is_favorite' => true]);
            $message = 'Đã thêm vào danh sách yêu thích';
        }

        return [
            'message' => $message,
            'room_id' => $roomId,
        ];
    }
}

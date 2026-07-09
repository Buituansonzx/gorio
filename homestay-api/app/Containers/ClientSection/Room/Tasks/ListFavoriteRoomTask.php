<?php

namespace App\Containers\ClientSection\Room\Tasks;

use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListFavoriteRoomTask extends ParentTask
{
    public function __construct()
    {
    }

    public function run($request)
    {
        $user = auth()->user();
        $favoriteRooms = $user->favoritedRooms()->with('medias')->paginate($request['page_size'] ?? 20);
        return $favoriteRooms;
    }
}

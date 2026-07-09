<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\AddFavoriteAction;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class FavoriteController extends ApiController
{
    public function favorite(Request $request, AddFavoriteAction $action)
    {
        $roomId = $request->id;
        $data = $action->run($roomId);
        return response()->json($data);
    }
}

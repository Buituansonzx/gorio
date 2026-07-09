<?php

namespace App\Containers\SharedSection\Room\UI\API\Controllers;

use App\Containers\SharedSection\Room\Actions\BlockRoomAction;
use App\Containers\SharedSection\Room\Actions\UnlockRoomAction;
use App\Containers\SharedSection\Room\Actions\UpdateBlockAction;
use App\Containers\SharedSection\Room\UI\API\Requests\BlockRoomRequest;
use App\Containers\SharedSection\Room\UI\API\Requests\UpdateBlockRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class BlockRoomController extends ApiController
{
    public function block(BlockRoomRequest $request, BlockRoomAction $action)
    {
        $data = $action->run($request);
        return response()->json(
            $data
        );
    }

    public function unlock(Request $request, UnlockRoomAction $action)
    {
        $roomLockId = $request->id;
        $data = $action->run($roomLockId);
        return response()->json([
                "message" => "Đã unlock phòng có id {$data->room_id} vào khung giờ {$data->start_time}-{$data->end_time}"
        ]);
    }

    public function update(UpdateBlockRequest $request,UpdateBlockAction $action)
    {
        $blockId = $request->id;
        $data = $action->run($blockId,$request->validated());
        return response()->json([
            "message" => "Cập nhật block thành công",
            "data" => $data
        ]);
    }
}

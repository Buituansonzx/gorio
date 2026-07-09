<?php

namespace App\Containers\SharedSection\Order\UI\API\Controllers;

use App\Containers\SharedSection\Order\UI\API\Requests\CalcRequest;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Controllers\ApiController;
use App\Ship\Traits\RoomPriceCalcTrait;
use Carbon\Carbon;
use phpDocumentor\Reflection\DocBlock\Description;

final class CalcController extends ApiController
{
    use RoomPriceCalcTrait;

    public function calc(CalcRequest $request)
    {
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $adults = $request->input('adults');
        $roomId = $request->input('room_id');
        $room = Room::findOrFail($roomId);


        return  response()->json( $this->calcPrice(
            Carbon::parse($request->check_in),
            Carbon::parse($request->check_out),
            (int) $request->adults,
            $room
        ));
    }

    public function calcWithVoucher(CalcRequest $request)
    {
        $roomId = $request->input('room_id') ?? $request->room_id;
        $room = Room::findOrFail($roomId);

        // Hỗ trợ lấy user qua guard API để dù API không có middleware bắt buộc auth vẫn xử lý voucher được nếu truyền token
        $user = auth('api')->user() ?? auth()->user();

        return response()->json($this->calcPrice(
            Carbon::parse($request->check_in),
            Carbon::parse($request->check_out),
            (int) $request->adults,
            $room,
            $user
        ));
    }
}

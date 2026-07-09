<?php

namespace App\Containers\AdminSection\Timeline\Actions;

use App\Containers\SharedSection\Order\Models\Order;
use App\Containers\SharedSection\Room\Models\RoomLock;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetEventsAction extends ParentAction
{
    public function run($data)
    {
        $orders = Order::where('status', Order::STATUS_PAID)
            ->where('check_in', '>=', $data['start_date'])
            ->where('check_out', '<=', $data['end_date'])
            ->when(!empty($data['host_id']), function ($q) use ($data) {
                $q->whereHas('room', fn ($qr) => $qr->where('host_id', $data['host_id']));
            })
            ->with(['room', 'voucher', 'user'])
            ->get();
        $roomLocks = RoomLock::where('start_time', '>=', $data['start_date'])
            ->where('end_time', '<=', $data['end_date'])
            ->when(!empty($data['host_id']), function ($q) use ($data) {
                $q->whereHas('room', fn ($qr) => $qr->where('host_id', $data['host_id']));
            })
            ->with('room')
            ->get();
        $lockEvents = $roomLocks->map(function ($lock) {
            return [
                'type' => 'lock',
                'room_id' => $lock->room_id,
                'id' => $lock->id,
                'start' => $lock->start_time->format('Y-m-d H:i:s'),
                'end' => $lock->end_time->format('Y-m-d H:i:s'),
                'created_at' => $lock->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $orderEvents = $orders->map(function ($order) {
            return [
                'type' => 'order',
                'room_id' => $order->room_id,
                'id' => $order->id,
                'code' => $order->code,
                'guest_name' => $order->guest_name,
                'guest_phone' => $order->guest_phone,
                'number_of_guests' => $order->number_of_guests,
                'created_by' => $order->user->name ?? 'N/A',
                'start' => $order->check_in,
                'end' => $order->check_out,
                'total' => $order->total,
                'voucher_code' => $order->voucher->code ?? null,
                'note' => $order->note,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            ];
        });
        $events = collect($orderEvents)
            ->merge($lockEvents)
            ->groupBy('room_id');

        return $events;
    }
}

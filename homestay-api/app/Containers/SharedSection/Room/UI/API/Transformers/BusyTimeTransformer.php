<?php

namespace App\Containers\SharedSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use Carbon\Carbon;

final class BusyTimeTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform($item): array
    {
        $isOrder = $item instanceof Order;
        $checkIn = Carbon::parse($isOrder ? $item->check_in : $item->start_time);
        $checkOut = Carbon::parse($isOrder ? $item->check_out : $item->end_time);
        $cleanDuration = $item->room->cleaning_duration ?? 0;
        $checkOut = $checkOut->copy()->subHour()->addHours($cleanDuration);
        return [
            'check_in'  => $checkIn->format('Y-m-d H:i:s'),
            'check_out' => $checkOut->format('Y-m-d H:i:s'),
            'type'      => $isOrder ? 'order' : 'lock',
        ];
    }
}

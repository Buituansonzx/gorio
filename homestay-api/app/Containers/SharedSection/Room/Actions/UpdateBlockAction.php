<?php

namespace App\Containers\SharedSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\RoomLock;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateBlockAction extends ParentAction
{
    public function run($blockId,$data)
    {
        $block = RoomLock::findOrFail($blockId);
        $block->update($data);
        return [
            'id' => $block->id,
            'start' => $block->start_time->format('Y-m-d H:i:s'),
            'end' => $block->end_time->format('Y-m-d H:i:s'),
        ];
    }
}

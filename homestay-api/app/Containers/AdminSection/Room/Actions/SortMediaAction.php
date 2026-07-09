<?php

namespace App\Containers\AdminSection\Room\Actions;

use App\Containers\SharedSection\Room\Models\Media;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;

final class SortMediaAction extends ParentAction
{
    public function run($roomId, $mediaData)
    {
        DB::transaction(function () use ($roomId, $mediaData) {

            $incomingIds = collect($mediaData)->pluck('id')->toArray();

            //Xóa những media không có trong danh sách gửi lên
            Media::where('room_id', $roomId)
                ->whereNotIn('id', $incomingIds)
                ->delete();

            foreach ($mediaData as $item) {
                Media::where('id', $item['id'])
                    ->where('room_id', $roomId)
                    ->update([
                        'sort_index' => $item['sort_index'],
                    ]);
            }

            Media::where('room_id', $roomId)
                ->update(['is_cover' => false]);

            $coverMediaId = Media::where('room_id', $roomId)
                ->orderBy('sort_index')
                ->value('id');

            if ($coverMediaId) {
                Media::where('id', $coverMediaId)
                    ->update(['is_cover' => true]);
            }
        });
        return Media::where('room_id', $roomId)
            ->orderByDesc('is_cover')
            ->orderBy('sort_index')
            ->get();
    }
}

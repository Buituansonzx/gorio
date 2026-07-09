<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

final class Media extends ParentModel
{
    use HasUuids;
     protected $table = 'medias';

    protected $guarded = [];

    protected $casts = ['variants' => 'array'];

    public function url(?string $path = null): string
    {
        return Storage::disk($this->disk)->url($path ?? $this->path);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function imageAreaGroup(): BelongsTo
    {
        return $this->belongsTo(RoomImageGroup::class, 'room_image_area_group_id');
    }

    /**
     * Scope để sắp xếp media theo sort_index
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_index', 'asc');
    }

    /**
     * Scope để lấy media theo danh sách room_id
     */
    public function scopeForRooms($query, array $roomIds)
    {
        return $query->whereIn('room_id', $roomIds);
    }

    /**
     * Select các trường cần thiết để tối ưu query
     */
    public function scopeWithEssentials($query)
    {
        return $query->select([
            'id',
            'room_id',
            'path',
            'disk',
            'variants',
            'sort_index',
            'is_cover',
            'room_image_area_group_id',
        ]);
    }
}

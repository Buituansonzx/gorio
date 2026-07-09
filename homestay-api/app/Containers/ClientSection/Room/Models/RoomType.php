<?php

namespace App\Containers\ClientSection\Room\Models;

use App\Containers\SharedSection\Room\Models\RoomType as SharedRoomType;

/**
 * Class RoomType
 * 
 * Client-specific RoomType model that extends the shared RoomType model.
 * 
 * @package App\Containers\ClientSection\Room\Models
 */
class RoomType extends SharedRoomType
{
    /**
     * Scope to get only active room types for clients
     */
    public function scopeActiveForClient($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get display name for client interface
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ($this->description ? ' - ' . $this->description : '');
    }
}

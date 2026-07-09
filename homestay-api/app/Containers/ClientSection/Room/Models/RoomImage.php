<?php

namespace App\Containers\ClientSection\Room\Models;

use App\Containers\SharedSection\Room\Models\RoomImage as SharedRoomImage;

/**
 * Class RoomImage
 *
 * Client-specific RoomImage model that extends the shared RoomImage model.
 *
 * @package App\Containers\ClientSection\Room\Models
 */
class RoomImage extends SharedRoomImage
{
    /**
     * Scope to get only primary images
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to get images ordered by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
                    ->orderBy('is_primary', 'desc');
    }

    /**
     * Get optimized image URL for client display
     */
    public function getOptimizedUrlAttribute(): string
    {
        // Add logic for image optimization/CDN if needed
        return $this->image_url;
    }
}

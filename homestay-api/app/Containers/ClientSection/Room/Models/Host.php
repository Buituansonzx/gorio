<?php

namespace App\Containers\ClientSection\Room\Models;

use App\Containers\SharedSection\Room\Models\Host as SharedHost;

/**
 * Class Host
 * 
 * Client-specific Host model that extends the shared Host model.
 * 
 * @package App\Containers\ClientSection\Room\Models
 */
class Host extends SharedHost
{
    /**
     * Scope to get only verified hosts for clients
     */
    public function scopeVerifiedForClient($query)
    {
        return $query->where('verified_status', true);
    }

    /**
     * Get host rating (placeholder for future rating system)
     */
    public function getRatingAttribute(): float
    {
        // TODO: Implement rating calculation based on reviews
        return 4.5;
    }

    /**
     * Get total rooms count for client display
     */
    public function getTotalRoomsCountAttribute(): int
    {
        return $this->rooms()->count();
    }

    /**
     * Get active rooms count for client display
     */
    public function getActiveRoomsCountAttribute(): int
    {
        return $this->rooms()->where('status', 'active')->count();
    }

    /**
     * Check if host has active rooms available
     */
    public function hasActiveRooms(): bool
    {
        return $this->active_rooms_count > 0;
    }

    /**
     * Get host profile summary for client view
     */
    public function getProfileSummaryAttribute(): array
    {
        return [
            'display_name' => $this->display_name,
            'is_verified' => $this->verified_status,
            'total_rooms' => $this->total_rooms_count,
            'active_rooms' => $this->active_rooms_count,
            'rating' => $this->rating,
            'has_business_profile' => !empty($this->business_name),
        ];
    }
}

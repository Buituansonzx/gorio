<?php

namespace App\Containers\ClientSection\Room\Models;

use App\Containers\SharedSection\Room\Models\House as SharedHouse;

/**
 * Class House
 * 
 * Client-specific House model that extends the shared House model.
 * 
 * @package App\Containers\ClientSection\Room\Models
 */
class House extends SharedHouse
{
    /**
     * Scope to get only houses with available rooms for clients
     */
    public function scopeWithAvailableRooms($query)
    {
        return $query->whereHas('rooms', function ($q) {
            $q->where('status', 'available');
        });
    }

    /**
     * Get house rating (placeholder for future rating system)
     */
    public function getRatingAttribute(): float
    {
        // TODO: Implement rating calculation based on room reviews
        return 4.3;
    }

    /**
     * Get the number of available rooms
     */
    public function getAvailableRoomsCountAttribute(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    /**
     * Get the minimum price among all rooms in this house
     */
    public function getMinPriceAttribute(): float
    {
        // TODO: Implement price calculation from room pricing
        return 0.0;
    }

    /**
     * Get the maximum price among all rooms in this house
     */
    public function getMaxPriceAttribute(): float
    {
        // TODO: Implement price calculation from room pricing
        return 0.0;
    }

    /**
     * Scope to get houses near a specific location
     */
    public function scopeNearLocation($query, $latitude, $longitude, $radius = 10)
    {
        return $query->selectRaw("
            *,
            (6371 * acos(cos(radians(?)) 
            * cos(radians(latitude)) 
            * cos(radians(longitude) - radians(?)) 
            + sin(radians(?)) 
            * sin(radians(latitude)))) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<', $radius)
        ->orderBy('distance');
    }

    /**
     * Get house verification status based on host
     */
    public function getIsVerifiedAttribute(): bool
    {
        return $this->host?->verified_status ?? false;
    }
}

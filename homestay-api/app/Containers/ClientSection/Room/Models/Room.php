<?php

namespace App\Containers\ClientSection\Room\Models;

use App\Containers\SharedSection\Room\Models\Room as SharedRoom;

/**
 * Class Room
 *
 * Client-specific Room model that extends the shared Room model.
 * Add client-specific methods and relationships here.
 *
 * @package App\Containers\ClientSection\Room\Models
 */
class Room extends SharedRoom
{
    /**
     * Client-specific methods can be added here
     * For example: client-specific scopes, accessors, mutators
     */

    /**
     * Scope to get only available rooms for clients
     */
    public function scopeAvailableForClient($query)
    {
        return $query->where('is_active', true)
                    ->whereHas('host', function ($q) {
                        $q->where('verified_status', true);
                    });
    }

    /**
     * Scope to get rooms from verified hosts only
     */
    public function scopeFromVerifiedHosts($query)
    {
        return $query->whereHas('host', function ($q) {
            $q->where('verified_status', true);
        });
    }

    /**
     * Override host relationship to use ClientSection Host model
     */
    public function host(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Host::class, 'host_id');
    }

    /**
     * Scope to get rooms within client's budget
     */
    public function scopeWithinBudget($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice) {
            $query->where('base_price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('base_price', '<=', $maxPrice);
        }

        return $query;
    }

    /**
     * Get formatted price for display
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->base_price, 0, ',', '.') . ' VND';
    }

    /**
     * Check if room allows instant booking
     */
    public function isInstantBookable(): bool
    {
        return $this->instant_booking && $this->status === 'active';
    }
}

<?php

namespace App\Containers\ClientSection\Room\Models;

use App\Containers\SharedSection\Room\Models\Attribute as SharedAttribute;

/**
 * Class Attribute
 * 
 * Client-specific Attribute model that extends the shared Attribute model.
 * 
 * @package App\Containers\ClientSection\Room\Models
 */
class Attribute extends SharedAttribute
{
    /**
     * Scope to get only attributes visible to clients
     */
    public function scopeVisibleToClient($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get icon for client display
     */
    public function getIconAttribute(): string
    {
        // Map attribute names to icons
        $iconMap = [
            'WiFi' => 'wifi',
            'Air Conditioning' => 'snowflake',
            'TV' => 'tv',
            'Kitchen' => 'utensils',
            'Parking' => 'car',
            'Pool' => 'swimming-pool',
        ];

        return $iconMap[$this->name] ?? 'home';
    }
}

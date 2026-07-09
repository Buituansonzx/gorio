<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class House extends ParentModel
{
    use HasUuids;

    protected $table = 'houses';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    /**
     * Relationship: House belongs to a host
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(Host::class, 'host_id');
    }

    /**
     * Relationship: House has many rooms
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'house_id');
    }

    /**
     * Scope: Get houses with active rooms
     */
    public function scopeWithActiveRooms($query)
    {
        return $query->whereHas('rooms', function ($q) {
            $q->where('is_active', true);
        });
    }

    /**
     * Get total rooms count
     */
    public function getTotalRoomsAttribute(): int
    {
        return $this->rooms()->count();
    }

    /**
     * Get active rooms count
     */
    public function getActiveRoomsAttribute(): int
    {
        return $this->rooms()->where('is_active', true)->count();
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}

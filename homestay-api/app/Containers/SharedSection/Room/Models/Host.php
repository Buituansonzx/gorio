<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Host extends ParentModel
{
    use HasUuids;

    protected $table = 'hosts';

    // Specify that we're using UUID as primary key
    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'id' => 'string',
        'verified_status' => 'boolean',
        'data' => 'array',
    ];

    /**
     * Relationship: Host belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Containers\AppSection\User\Models\User::class, 'user_id');
    }

    /**
     * Relationship: Host has many houses
     */
    public function houses(): HasMany
    {
        return $this->hasMany(House::class, 'host_id');
    }

    /**
     * Relationship: Host has many rooms (direct relationship for easier queries)
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'host_id');
    }

    /**
     * Scope: Get only verified hosts
     */
    public function scopeVerified($query)
    {
        return $query->where('verified_status', true);
    }

    /**
     * Scope: Get hosts with active rooms
     */
    public function scopeWithActiveRooms($query)
    {
        return $query->whereHas('rooms', function ($q) {
            $q->where('is_active', true);
        });
    }

    /**
     * Check if host is verified
     */
    public function isVerified(): bool
    {
        return $this->verified_status;
    }

    /**
     * Get host display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->business_name ?: $this->user->name ?? 'Unnamed Host';
    }
}

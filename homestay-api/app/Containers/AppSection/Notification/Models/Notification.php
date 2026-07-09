<?php

namespace App\Containers\AppSection\Notification\Models;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Notification extends ParentModel
{
    use HasUuids,SoftDeletes;

    protected $table = 'notifications';

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    CONST TYPE_BOOKING = 'booking';
    CONST TYPE_OFFER = 'offer';
    CONST TYPE_SYSTEM = 'system';
    CONST TYPE_REMINDER = 'reminder';
    CONST TRIP_SCREEN = 'trip_screen';
    CONST HOME_SCREEN = 'home_screen';


    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_users')
            ->withPivot(['is_read', 'read_at'])
            ->withTimestamps();
    }

    public function getIsReadAttribute()
    {
        $user = $this->users->first();
        return $user ? $user->pivot->is_read : false;
    }
}

<?php

namespace App\Containers\SharedSection\Order\Models;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Room\Models\Review;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Order extends ParentModel
{
    use HasUuids, SoftDeletes;

    protected $table = 'orders';

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'data' => 'array',
    ];
    const  STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    const TIME_HOLD_MINUTES = 15;

    const CODE_REQUEST_BOOKING = 'request_booking';
    const CODE_SUCCESS_BOOKING = 'success_booking';

    /**
     * Relationship: Order belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    /**
     * Relationship: Order belongs to a room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function payment(){
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function voucher(){
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_id', 'id');
    }
    public function scopeActiveForBooking($query)
    {
        return $query
            ->where(function ($q) {
                $q->where('status', self::STATUS_PAID)
                    ->orWhere(function ($q2) {
                        $q2->where('status', self::STATUS_PENDING)
                            ->where('created_at', '>=', now()->subMinutes(self::TIME_HOLD_MINUTES));
                    });
            });
    }
}

<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Order\Models\Order;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Review extends ParentModel
{
    use HasUuids;

    protected $table = 'reviews';

    protected $guarded = [];


    /**
     * Relationship: Review belongs to a user
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * Relationship: Review belongs to an order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}

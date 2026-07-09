<?php

namespace App\Containers\SharedSection\Order\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Payment extends ParentModel
{
    use HasUuids;
      protected $table = 'payments';

      protected $guarded = [];

      public function order(){
          return $this->belongsTo(Order::class, 'order_id');
      }
}

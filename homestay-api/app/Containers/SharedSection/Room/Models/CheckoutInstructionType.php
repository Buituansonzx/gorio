<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class CheckoutInstructionType extends ParentModel
{
    use HasUuids, HasTranslations;

    protected $table = 'checkout_instructions_types';

    protected $guarded = [];

    public array $translatable = ['name'];

    public $timestamps = false;

    public function rooms(){
        return $this->belongsToMany(Room::class, 'room_checkout_instructions_type')->withPivot('content');
    }
}

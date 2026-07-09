<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class Province extends ParentModel
{
    use HasUuids, HasTranslations;

    protected $table = 'provinces';

    protected $fillable = [
        'code',
        'name'
    ];

    public $timestamps = false;

    public array $translatable = ['name'];
    /**
     * Relationship: Province can have many districts
     */
    public function districts(){
        return $this->hasMany(District::class, 'province_id');
    }
}

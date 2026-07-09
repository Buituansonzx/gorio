<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Translatable\HasTranslations;

final class District extends ParentModel
{
    use HasUuids, HasTranslations;

    protected $table = 'districts';

    protected $fillable = [
        'code',
        'province_id',
        'name'
    ];

    public $timestamps = false;

    public array $translatable = ['name'];
    /**
     * Relationship: District belongs to a province
     */
    public function province(){
        return $this->belongsTo(Province::class, 'province_id');
    }
}

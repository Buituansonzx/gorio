<?php

namespace App\Containers\SharedSection\Room\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

final class PriceRatio extends ParentModel
{
    use HasUuids, HasTranslations;
    protected $table = 'price_ratios';

    protected $fillable = [
        'code',
        'ratio',
        'name',
        'description',
    ];

    protected $casts = [
        'ratio' => 'decimal:2',
        'name' => 'array',
        'description' => 'array',
    ];

    public array $translatable = ['name', 'description'];

    /**
     * Relationship: PriceRatio has many room discount policies
     */
    public function roomDiscountPolicies(): HasMany
    {
        return $this->hasMany(RoomDiscountPolicy::class, 'price_ratio_id');
    }
}

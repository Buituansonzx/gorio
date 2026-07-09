<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\PriceRatio;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of PriceRatio
 *
 * @extends ParentRepository<TModel>
 */
final class PriceRatioRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'code' => '=',
        'ratio' => '=',
        'name' => 'like',
        'description' => 'like',
        'created_at' => 'like',
        'updated_at' => 'like',
    ];

    public function model(): string
    {
        return PriceRatio::class;
    }
}

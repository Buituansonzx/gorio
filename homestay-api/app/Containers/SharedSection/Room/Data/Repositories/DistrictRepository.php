<?php

namespace App\Containers\SharedSection\Room\Data\Repositories;

use App\Containers\SharedSection\Room\Models\District;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of District
 *
 * @extends ParentRepository<TModel>
 */
final class DistrictRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    public function model(): string
    {
        return District::class;
    }

    public function getDistricts(string $provinceId)
    {
        return $this->model->where('province_id', $provinceId)->get();
    }
}

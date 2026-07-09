<?php

namespace App\Containers\ClientSection\Room\Data\Repositories;

use Apiato\Http\Resources\Collection;
use App\Containers\SharedSection\Room\Models\Province;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Province
 *
 * @extends ParentRepository<TModel>
 */
final class GetProvinceRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];


    public function model(): string
    {
        return Province::class;
    }

    public function getProvinces()
    {
        return $this->model->with('districts')->get();
    }
}

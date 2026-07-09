<?php

namespace App\Containers\SharedSection\OtpDevice\Data\Repositories;

use App\Containers\SharedSection\OtpDevice\Models\OtpDevice;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of OtpDevice
 *
 * @extends ParentRepository<TModel>
 */
final class OtpDeviceRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    public function model():string
    {
        return OtpDevice::class;
    }
}

<?php

namespace App\Containers\AdminSection\House\Actions;

use App\Containers\SharedSection\Room\Models\House;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListingHouseAction extends ParentAction
{
    public function run($data)
    {
        return House::query()->with('rooms', 'district', 'rooms.checkinMethods','rooms.checkoutInstructionType')
            ->when(!empty($data['search']), function ($q) use ($data) {
                $search = $data['search'];

                $q->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('address', 'LIKE', "%{$search}%");
                });
            })
            ->when(array_key_exists('is_active', $data) && $data['is_active'] !== '' && $data['is_active'] !== null, function ($q) use ($data) {
                $q->where('is_active', (int) $data['is_active']);
            })
            ->when(!empty($data['district_id']), function ($q) use ($data) {
                $q->where('district_id', $data['district_id']);
            })
            ->paginate($data['page_size'] ?? 20);
    }
}

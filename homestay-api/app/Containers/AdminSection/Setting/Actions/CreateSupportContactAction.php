<?php

namespace App\Containers\AdminSection\Setting\Actions;

use App\Containers\AdminSection\Setting\UI\API\Requests\CreateSupportContactRequest;
use App\Containers\SharedSection\Room\Models\SupportContact;
use App\Ship\Parents\Actions\Action as ParentAction;

class CreateSupportContactAction extends ParentAction
{
    public function run(CreateSupportContactRequest $request)
    {
        $data = $request->validated();

        return SupportContact::create($data);
    }
}

<?php

namespace App\Containers\AdminSection\Setting\Actions;

use App\Containers\AdminSection\Setting\UI\API\Requests\GetAllSupportContactsRequest;
use App\Containers\SharedSection\Room\Models\SupportContact;
use App\Ship\Parents\Actions\Action as ParentAction;

class GetAllSupportContactsAction extends ParentAction
{
    public function run(GetAllSupportContactsRequest $request)
    {
        return SupportContact::orderBy('created_at', 'desc')->get();
    }
}

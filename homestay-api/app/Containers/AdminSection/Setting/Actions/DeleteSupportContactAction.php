<?php

namespace App\Containers\AdminSection\Setting\Actions;

use App\Containers\AdminSection\Setting\UI\API\Requests\DeleteSupportContactRequest;
use App\Containers\SharedSection\Room\Models\SupportContact;
use App\Ship\Parents\Actions\Action as ParentAction;

class DeleteSupportContactAction extends ParentAction
{
    public function run(DeleteSupportContactRequest $request)
    {
        $contact = SupportContact::findOrFail($request->id);
        return $contact->delete();
    }
}

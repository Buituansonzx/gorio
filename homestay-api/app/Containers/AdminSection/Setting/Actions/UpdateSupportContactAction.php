<?php

namespace App\Containers\AdminSection\Setting\Actions;

use App\Containers\AdminSection\Setting\UI\API\Requests\UpdateSupportContactRequest;
use App\Containers\SharedSection\Room\Models\SupportContact;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;

class UpdateSupportContactAction extends ParentAction
{
    public function run(UpdateSupportContactRequest $request)
    {
        $data = collect($request->validated())->filter(function ($value) {
            return $value !== null && $value !== '';
        })->toArray();

        $contact = SupportContact::findOrFail($request->id);
        $contact->update($data);

        return $contact;
    }
}

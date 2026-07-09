<?php

namespace App\Containers\AdminSection\Setting\UI\API\Controllers;

use App\Containers\AdminSection\Setting\Actions\CreateSupportContactAction;
use App\Containers\AdminSection\Setting\Actions\DeleteSupportContactAction;
use App\Containers\AdminSection\Setting\Actions\GetAllSupportContactsAction;
use App\Containers\AdminSection\Setting\Actions\UpdateSupportContactAction;
use App\Containers\AdminSection\Setting\UI\API\Requests\CreateSupportContactRequest;
use App\Containers\AdminSection\Setting\UI\API\Requests\DeleteSupportContactRequest;
use App\Containers\AdminSection\Setting\UI\API\Requests\GetAllSupportContactsRequest;
use App\Containers\AdminSection\Setting\UI\API\Requests\UpdateSupportContactRequest;
use App\Ship\Parents\Controllers\ApiController;

class SupportContactController extends ApiController
{
    public function getAllSupportContacts(GetAllSupportContactsRequest $request, GetAllSupportContactsAction $action)
    {
        $contacts = $action->run($request);
        return response()->json([
            'success' => true,
            'data' => $contacts
        ]);
    }

    public function createSupportContact(CreateSupportContactRequest $request, CreateSupportContactAction $action)
    {
        $contact = $action->run($request);
        return response()->json([
            'success' => true,
            'message' => 'Tạo liên hệ hỗ trợ thành công',
            'data' => $contact
        ], 201);
    }

    public function updateSupportContact(UpdateSupportContactRequest $request, UpdateSupportContactAction $action)
    {
        $contact = $action->run($request);
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật liên hệ hỗ trợ thành công',
            'data' => $contact
        ]);
    }

    public function deleteSupportContact(DeleteSupportContactRequest $request, DeleteSupportContactAction $action)
    {
        $action->run($request);
        return response()->json([
            'success' => true,
            'message' => 'Xóa liên hệ hỗ trợ thành công'
        ]);
    }
}

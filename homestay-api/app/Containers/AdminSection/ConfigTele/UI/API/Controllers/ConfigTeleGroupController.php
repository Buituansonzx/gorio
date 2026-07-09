<?php

namespace App\Containers\AdminSection\ConfigTele\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AdminSection\ConfigTele\Actions\AddHouseToConfigTeleGroupAction;
use App\Containers\AdminSection\ConfigTele\Actions\CreateConfigTeleGroupAction;
use App\Containers\AdminSection\ConfigTele\Actions\DeleteConfigTeleGroupAction;
use App\Containers\AdminSection\ConfigTele\Actions\FindConfigTeleGroupByIdAction;
use App\Containers\AdminSection\ConfigTele\Actions\GetAllConfigTeleGroupsAction;
use App\Containers\AdminSection\ConfigTele\Actions\UpdateConfigTeleGroupAction;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\AddHouseToConfigTeleGroupRequest;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\CreateConfigTeleGroupRequest;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\DeleteConfigTeleGroupRequest;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\FindConfigTeleGroupByIdRequest;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\GetAllConfigTeleGroupsRequest;
use App\Containers\AdminSection\ConfigTele\UI\API\Requests\UpdateConfigTeleGroupRequest;
use App\Containers\AdminSection\ConfigTele\UI\API\Transformers\ConfigTeleGroupTransformer;
use App\Ship\Parents\Controllers\ApiController;

class ConfigTeleGroupController extends ApiController
{
    public function createConfigTeleGroup(CreateConfigTeleGroupRequest $request)
    {
        $configTeleGroup = app(CreateConfigTeleGroupAction::class)->run($request);

        return Response::create($configTeleGroup, ConfigTeleGroupTransformer::class);
    }

    public function findConfigTeleGroupById(FindConfigTeleGroupByIdRequest $request)
    {
        $configTeleGroup = app(FindConfigTeleGroupByIdAction::class)->run($request);

        return Response::create($configTeleGroup, ConfigTeleGroupTransformer::class);
    }

    public function getAllConfigTeleGroups(GetAllConfigTeleGroupsRequest $request)
    {
        $configTeleGroups = app(GetAllConfigTeleGroupsAction::class)->run($request);

        return Response::create($configTeleGroups, ConfigTeleGroupTransformer::class);
    }

    public function updateConfigTeleGroup(UpdateConfigTeleGroupRequest $request)
    {
        $configTeleGroup = app(UpdateConfigTeleGroupAction::class)->run($request);

        return Response::create($configTeleGroup, ConfigTeleGroupTransformer::class);
    }

    public function deleteConfigTeleGroup(DeleteConfigTeleGroupRequest $request)
    {
        app(DeleteConfigTeleGroupAction::class)->run($request);

        return response()->json([
            'message' => 'Delete resource successfully.',
        ]);
    }

    public function addHouseToConfigTeleGroup(AddHouseToConfigTeleGroupRequest $request)
    {
        $configTeleGroup = app(AddHouseToConfigTeleGroupAction::class)->run($request);

        return Response::create($configTeleGroup, ConfigTeleGroupTransformer::class);
    }
}

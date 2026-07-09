<?php

/**
 * @apiGroup           ConfigTeleGroup
 * @apiName            addHouseToConfigTeleGroup
 * @api                {post} /v1/admin/config-tele-groups/:id/houses Add House To Config Tele Group
 * @apiDescription     Add houses to an existing Config Tele Group
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 */

use App\Containers\AdminSection\ConfigTele\UI\API\Controllers\ConfigTeleGroupController;
use Illuminate\Support\Facades\Route;

Route::post('admin/config-tele-groups/{id}/houses', [ConfigTeleGroupController::class, 'addHouseToConfigTeleGroup'])
    ->name('api_admin_config_tele_groups_add_house')
    ->middleware(['auth:api']);

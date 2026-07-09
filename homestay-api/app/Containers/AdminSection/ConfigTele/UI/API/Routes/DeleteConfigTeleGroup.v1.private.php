<?php

/**
 * @apiGroup           ConfigTeleGroup
 * @apiName            deleteConfigTeleGroup
 * @api                {delete} /v1/config-tele-groups/:id Delete Config Tele Group
 * @apiDescription     Delete
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 */

use App\Containers\AdminSection\ConfigTele\UI\API\Controllers\ConfigTeleGroupController;
use Illuminate\Support\Facades\Route;

Route::delete('admin/config-tele-groups/{id}', [ConfigTeleGroupController::class, 'deleteConfigTeleGroup'])
    ->name('api_admin_config_tele_groups_delete')
    ->middleware(['auth:api']);

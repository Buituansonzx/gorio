<?php

/**
 * @apiGroup           ConfigTeleGroup
 * @apiName            getAllConfigTeleGroups
 * @api                {get} /v1/config-tele-groups Get All Config Tele Groups
 * @apiDescription     Get All
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 */

use App\Containers\AdminSection\ConfigTele\UI\API\Controllers\ConfigTeleGroupController;
use Illuminate\Support\Facades\Route;

Route::get('admin/config-tele-groups', [ConfigTeleGroupController::class, 'getAllConfigTeleGroups'])
    ->name('api_admin_config_tele_groups_get_all')
    ->middleware(['auth:api']);

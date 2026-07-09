<?php

/**
 * @apiGroup           ConfigTeleGroup
 * @apiName            updateConfigTeleGroup
 * @api                {patch} /v1/config-tele-groups/:id Update Config Tele Group
 * @apiDescription     Update
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 */

use App\Containers\AdminSection\ConfigTele\UI\API\Controllers\ConfigTeleGroupController;
use Illuminate\Support\Facades\Route;

Route::patch('admin/config-tele-groups/{id}', [ConfigTeleGroupController::class, 'updateConfigTeleGroup'])
    ->name('api_admin_config_tele_groups_update')
    ->middleware(['auth:api']);

<?php

/**
 * @apiGroup           ConfigTeleGroup
 * @apiName            findConfigTeleGroupById
 * @api                {get} /v1/config-tele-groups/:id Find Config Tele Group By Id
 * @apiDescription     Find by ID
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 */

use App\Containers\AdminSection\ConfigTele\UI\API\Controllers\ConfigTeleGroupController;
use Illuminate\Support\Facades\Route;

Route::get('admin/config-tele-groups/{id}', [ConfigTeleGroupController::class, 'findConfigTeleGroupById'])
    ->name('api_admin_config_tele_groups_find_by_id')
    ->middleware(['auth:api']);

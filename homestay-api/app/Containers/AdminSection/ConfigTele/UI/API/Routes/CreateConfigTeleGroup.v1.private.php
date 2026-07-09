<?php

/**
 * @apiGroup           ConfigTeleGroup
 * @apiName            createConfigTeleGroup
 * @api                {post} /v1/config-tele-groups Create Config Tele Group
 * @apiDescription     Create Configuration for Telegram Group
 * @apiVersion         1.0.0
 * @apiPermission      Authenticated User
 */

use App\Containers\AdminSection\ConfigTele\UI\API\Controllers\ConfigTeleGroupController;
use Illuminate\Support\Facades\Route;

Route::post('admin/config-tele-groups', [ConfigTeleGroupController::class, 'createConfigTeleGroup'])
    ->name('api_admin_config_tele_groups_create')
    ->middleware(['auth:api']);

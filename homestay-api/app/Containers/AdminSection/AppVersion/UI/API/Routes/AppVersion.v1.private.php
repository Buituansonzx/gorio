<?php

use App\Containers\AdminSection\AppVersion\UI\API\Controllers\AppVersionController;
use Illuminate\Support\Facades\Route;

Route::get('admin/app-versions', [AppVersionController::class, 'getAllAppVersions'])
    ->name('api_admin_app_versions_get_all');

Route::post('admin/app-versions', [AppVersionController::class, 'createAppVersion'])
    ->name('api_admin_app_versions_create');

Route::put('admin/app-versions/{id}', [AppVersionController::class, 'updateAppVersion'])
    ->name('api_admin_app_versions_update');

Route::delete('admin/app-versions/{id}', [AppVersionController::class, 'deleteAppVersion'])
    ->name('api_admin_app_versions_delete');

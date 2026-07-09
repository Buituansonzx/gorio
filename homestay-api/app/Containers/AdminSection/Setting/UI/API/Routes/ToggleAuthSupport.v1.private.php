<?php

use App\Containers\AdminSection\Setting\UI\API\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::post('admin/settings/toggle-auth-support', [SettingController::class, 'toggleAuthSupport'])
    ->middleware(['auth:api']);

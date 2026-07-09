<?php

use App\Containers\AdminSection\Setting\UI\API\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('admin/settings/auth-support', [SettingController::class, 'getAuthSupportStatus'])
    ->middleware(['auth:api']);

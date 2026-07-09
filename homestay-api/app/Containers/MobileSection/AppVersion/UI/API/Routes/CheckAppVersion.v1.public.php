<?php

use App\Containers\MobileSection\AppVersion\UI\API\Controllers\CheckAppVersionController;
use Illuminate\Support\Facades\Route;

Route::get('mobile/app-versions/check', [CheckAppVersionController::class, 'checkVersion'])
    ->name('api_mobile_app_version_check')
    ->middleware(['api']);

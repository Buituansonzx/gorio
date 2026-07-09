<?php

use App\Containers\AppSection\Auth\UI\API\Controllers\RefreshTokenController;
use Illuminate\Support\Facades\Route;

Route::post('auth/refresh-token', [RefreshTokenController::class, 'refreshToken'])
    ->name('auth.refresh-token');

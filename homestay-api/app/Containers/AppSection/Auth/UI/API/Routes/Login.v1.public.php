<?php

use App\Containers\AppSection\Auth\UI\API\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [LoginController::class, 'login']);

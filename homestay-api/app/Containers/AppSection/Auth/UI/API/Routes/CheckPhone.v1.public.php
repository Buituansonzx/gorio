<?php

use App\Containers\AppSection\Auth\UI\API\Controllers\CheckPhoneController;
use Illuminate\Support\Facades\Route;

Route::post('auth/check-phone', [CheckPhoneController::class, 'checkPhone']);

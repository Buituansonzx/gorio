<?php

/**
 * @apiGroup           Authentication
 *
 * @apiName            RegisterUser
 *
 * @api                {post} /v1/register Register User
 *
 * @apiDescription     Register a new user
 *
 * @apiVersion         1.0.0
 *
 * @apiPermission      none
 *
 * @apiHeader          {String} accept=application/json
 *
 * @apiBody            {Object} phone Phone number as object with country_code and phone_number
 * @apiBody            {String} phone.country_code Country code (e.g., "VN")
 * @apiBody            {String} phone.phone_number Phone number (e.g., "0902123456")
 * @apiBody            {String} first_name First name
 * @apiBody            {String} last_name Last name  
 * @apiBody            {Date} birth Birth date in Y-m-d format
 * @apiBody            {String} email Email address
 * @apiBody            {String} [password] Optional password (will be auto-generated if not provided)
 *
 * @apiUse             UserSuccessSingleResponse
 */

use App\Containers\AppSection\Authentication\UI\API\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterUserController::class)
    ->middleware(['api']);

<?php

use App\Containers\AdminSection\Setting\UI\API\Controllers\SupportContactController;
use Illuminate\Support\Facades\Route;

Route::post('admin/support-contacts', [SupportContactController::class, 'createSupportContact'])
    ->name('api_setting_create_support_contact');

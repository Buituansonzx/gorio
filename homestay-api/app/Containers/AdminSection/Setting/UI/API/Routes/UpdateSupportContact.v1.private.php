<?php

use App\Containers\AdminSection\Setting\UI\API\Controllers\SupportContactController;
use Illuminate\Support\Facades\Route;

Route::put('admin/support-contacts/{id}', [SupportContactController::class, 'updateSupportContact'])
    ->name('api_setting_update_support_contact');

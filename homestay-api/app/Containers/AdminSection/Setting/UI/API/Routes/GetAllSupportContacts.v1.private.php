<?php

use App\Containers\AdminSection\Setting\UI\API\Controllers\SupportContactController;
use Illuminate\Support\Facades\Route;

Route::get('admin/support-contacts', [SupportContactController::class, 'getAllSupportContacts'])
    ->name('api_setting_get_all_support_contacts');

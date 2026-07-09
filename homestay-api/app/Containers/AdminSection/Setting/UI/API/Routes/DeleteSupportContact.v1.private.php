<?php

use App\Containers\AdminSection\Setting\UI\API\Controllers\SupportContactController;
use Illuminate\Support\Facades\Route;

Route::delete('admin/support-contacts/{id}', [SupportContactController::class, 'deleteSupportContact'])
    ->name('api_setting_delete_support_contact');

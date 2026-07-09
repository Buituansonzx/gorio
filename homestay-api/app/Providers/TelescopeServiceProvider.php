<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TelescopeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope disabled for this application. No-op provider.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // No bootstrap logic since Telescope is not used.
    }
}

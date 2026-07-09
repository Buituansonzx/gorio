<?php

namespace App\Containers\AppSection\Authentication\Providers;

use App\Ship\Parents\Providers\ServiceProvider as ParentServiceProvider;

final class MainServiceProvider extends ParentServiceProvider
{
    public function register(): void
    {
        // Load config file
        $this->mergeConfigFrom(
            __DIR__ . '/../Configs/appSection-authentication.php',
            'appSection-authentication'
        );
    }

    public function boot(): void
    {
        // Boot logic if needed
    }
}

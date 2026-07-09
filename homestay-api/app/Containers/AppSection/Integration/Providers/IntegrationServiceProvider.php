<?php

namespace App\Containers\AppSection\Integration\Providers;

use App\Ship\Parents\Providers\ServiceProvider as ParentServiceProvider;

final class IntegrationServiceProvider extends ParentServiceProvider
{
    public function register(): void
    {
        // Load integration configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../Configs/integration.php',
            'integration'
        );
    }

    public function boot(): void
    {
        // Register integration services
        $this->registerPaymentGateways();
        $this->registerNotificationServices();
    }

    private function registerPaymentGateways(): void
    {
        // Register payment gateway bindings
        if (config('integration.payment.stripe.enabled')) {
            // Bind Stripe service
        }

        if (config('integration.payment.vnpay.enabled')) {
            // Bind VNPay service
        }
    }

    private function registerNotificationServices(): void
    {
        // Register notification service bindings
    }
}
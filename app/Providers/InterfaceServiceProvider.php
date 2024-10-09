<?php

namespace App\Providers;

use App\Interfaces\ISubscriptionService;
use App\Interfaces\IUserService;
use App\Services\SubscriptionService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            IUserService::class,
            UserService::class
        );
        $this->app->bind(
            ISubscriptionService::class,
            SubscriptionService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

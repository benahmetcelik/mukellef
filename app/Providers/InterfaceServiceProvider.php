<?php

namespace App\Providers;

use App\Interfaces\ISubscriptionService;
use App\Interfaces\ISubscriptionV2Service;
use App\Interfaces\IUserService;
use App\Interfaces\IUserSubscriptionService;
use App\Services\SubscriptionService;
use App\Services\SubscriptionV2Service;
use App\Services\UserService;
use App\Services\UserSubscriptionService;
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
        $this->app->bind(
            IUserSubscriptionService::class,
            UserSubscriptionService::class
        );
        $this->app->bind(
            ISubscriptionV2Service::class,
            SubscriptionV2Service::class
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

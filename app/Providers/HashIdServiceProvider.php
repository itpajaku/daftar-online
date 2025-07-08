<?php

namespace App\Providers;

use App\Service\HashId;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Vinkla\Hashids\HashidsManager;
use App\Service\HashRouteId;

class HashIdServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton('hashid', function ($app) {
            return new HashId($app->make(HashidsManager::class));
        });
    }
}

<?php

namespace App\Providers;

use App\Models\Setting;
use App\Service\HashId;
use App\Service\HashRouteId;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

use function Livewire\on;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        on('call', function ($component, $method, $params, $addEffect, $earlyReturn) {
            if ($method === 'toJSON') {
                $earlyReturn([]);
            }
        });

        $this->app->singleton('settings', function () {
            return Cache::rememberForever('settings', function () {
                return Setting::all()->pluck('value', 'key')->toArray();
            });
        });

        Route::bind('hash_id', function ($value) {
            return new HashRouteId(app(HashId::class), $value);
        });
    }
}

<?php

namespace App\Providers;

use App\Models\Sysconf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SysconfServiceProvider extends ServiceProvider
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
        // $sysconf = Sysconf::where("id", ">=", 61)->get();
        // View::share("sys_conf", $sysconf->pluck('value', 'name')->toArray());
    }
}

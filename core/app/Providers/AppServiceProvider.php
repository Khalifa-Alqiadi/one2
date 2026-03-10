<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fore HTTPs if enabled to fix any problems with mixed contents
        if (!empty(@$_SERVER['https']) || @$_SERVER['HTTPS'] == 'on' || (!empty(@$_SERVER['HTTP_X_FORWARDED_PROTO']) && @$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        Paginator::useBootstrap();
    }
}

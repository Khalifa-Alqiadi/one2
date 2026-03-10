<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\Helper;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the Helper class as a singleton
        $this->app->singleton('Helper', function () {
            return new Helper;
        });

        // Alternatively, you can use class_alias to create a global alias
        try {
            class_alias(Helper::class, 'Helper');
        }catch (\Exception $exception){

        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

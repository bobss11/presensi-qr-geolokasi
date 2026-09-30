<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        if (
            $this->app->environment('production') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (isset($_SERVER['HTTP_HOST']) && (
                str_contains($_SERVER['HTTP_HOST'], 'trycloudflare.com') ||
                str_contains($_SERVER['HTTP_HOST'], 'railway.app') ||
                str_contains($_SERVER['HTTP_HOST'], 'up.railway.app') ||
                str_contains($_SERVER['HTTP_HOST'], 'onrender.com')
            ))
        ) {
            URL::forceScheme('https');
        }
    }
}

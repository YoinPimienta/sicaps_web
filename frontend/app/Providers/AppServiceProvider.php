<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Forzar HTTPS en producción, en Render o detrás de cualquier proxy con SSL
        if (
            app()->environment('production') ||
            request()->header('x-forwarded-proto') === 'https' ||
            !empty(env('RENDER')) ||
            (request()->server('HTTP_X_FORWARDED_PROTO') === 'https') ||
            str_contains(request()->getHost(), 'onrender.com')
        ) {
            URL::forceScheme('https');
        }
    }
}

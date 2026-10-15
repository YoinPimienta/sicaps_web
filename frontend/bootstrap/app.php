<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Confiar en proxies inversos de la nube (como Render) para SSL / X-Forwarded-Proto
        $middleware->trustProxies(at: '*');

        // Middleware de autenticacion por JWT de sesion
        $middleware->alias([
            'auth.jwt' => \App\Http\Middleware\EnsureAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

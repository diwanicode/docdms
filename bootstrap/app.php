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
        $web = [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\HandleBusinessLanguage::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \App\Http\Middleware\HandleSecurityHeaders::class,
        ];

        // Only enable CSP outside local environment
       if (env('APP_ENV') !== 'local') {
            $web[] = \Spatie\Csp\AddCspHeaders::class;
        }

        $middleware->web(append: $web);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

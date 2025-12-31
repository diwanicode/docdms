<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Illuminate\Http\Request; 
use Inertia\Inertia;

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
            \App\Http\Middleware\HandleCors::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,   
            \Spatie\Csp\AddCspHeaders::class,
            \App\Http\Middleware\HandleSecurityHeaders::class 
        ];
 
        $middleware->web(append: $web);
        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //TODO Emina add to admin panel all errors
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
        
            // Determine status code based on exception type
            switch (true) {
                case $exception instanceof AuthorizationException:
                    $status = 403;
                    Log::warning('Unauthorized access attempt.', [
                        'message' => $exception->getMessage(),
                        'trace' => $exception->getTraceAsString(),
                    ]);
                    break;

                case $exception instanceof ModelNotFoundException:
                    $status = 404;
                    Log::error('Model not found.', [
                        'model' => $exception->getModel(),
                        'ids'   => $exception->getIds(),
                        'message' => $exception->getMessage(),
                        'trace' => $exception->getTraceAsString(),
                        'route_params' => request()->route()?->parameters(),
                        'path' => request()->path(),
                    ]);
                    break;

                case $exception instanceof QueryException:
                    $status = 500;
                    Log::error('Database Query Exception.', [
                        'sql' => $exception->getSql(),
                        'bindings' => $exception->getBindings(),
                        'message' => $exception->getMessage(),
                        'trace' => $exception->getTraceAsString(),
                    ]);
                    break;

                default:
                    // If response already has a status code, use it
                    $status = $response->getStatusCode() ?: 500;
                    if (in_array($status, [500, 503, 404, 403])) {
                        Log::error('Unhandled Exception', [
                            'status' => $status,
                            'message' => $exception->getMessage(),
                            'file'    => $exception->getFile(),
                            'line'    => $exception->getLine(),
                            'trace'   => $exception->getTraceAsString(),
                        ]);
                    }
                    break;
            }
        
            // Return custom Inertia error views for known statuses
            if (in_array($status, [500, 503, 404, 403,429])) {
                Inertia::share(app(HandleInertiaRequests::class)->share($request));
                return Inertia::render('Error/NotFound', [
                    'status' => $status,
                ])->toResponse($request)
                ->setStatusCode($status);
            }

            if ($status === 419) {
                Inertia::share(app(HandleInertiaRequests::class)->share($request));
                return Inertia::render('Error/NotFound', [
                    'status' => $status,
                ])->toResponse($request)
                ->setStatusCode($status);
            }

            // Otherwise, return the default Laravel response
            return $response;
        });

    })->create();

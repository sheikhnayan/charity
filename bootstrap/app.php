<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            \App\Http\Middleware\AnalyticsTrackingMiddleware::class,
            \App\Http\Middleware\TrackUniqueVisitor::class,
        ]);
        
        // Add CORS middleware globally for API routes
        $middleware->api([
            \App\Http\Middleware\CorsMiddleware::class,
        ]);
        
        // Exclude CSRF for public tracking endpoints (Hotjar-style tracking)
        $middleware->validateCsrfTokens(except: [
            'api/session-recording/start',
            'api/session-recording/events',
            'api/heatmap/click',
            'api/heatmap/move',
            'api/heatmap/scroll',
            'api/heatmap/screenshot',
            'api/heatmap/screenshot/capture',
            'users/investor-profile/save',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

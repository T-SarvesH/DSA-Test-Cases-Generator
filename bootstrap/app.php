<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Get the application instance early to configure paths
// This tries to ensure paths are set before cache attempts
$app = new Application(dirname(__DIR__)); // Line 10 (approx)

// Configure storage and bootstrap cache paths for serverless environment
if (isset($_ENV['VERCEL_ENV']) || isset($_ENV['VERCEL'])) { // Line 14 (approx)
    $app->useStoragePath('/tmp/storage'); // Line 15
    $app->useBootstrapPath('/tmp/bootstrap'); // Line 16
}

// Now, proceed with the standard Laravel application configuration
return $app->configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
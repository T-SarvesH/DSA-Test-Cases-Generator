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
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Add this block below the '->create();' line
$app = \Illuminate\Foundation\Application::getInstance(); // Get the application instance

// Bind the public path to ensure it's correct for serving assets
$app->bind('path.public', function () {
    return base_path('public');
});

// Configure storage and bootstrap cache paths for serverless environment
if (isset($_ENV['VERCEL_ENV']) || isset($_ENV['VERCEL'])) { // Vercel environment variables
    $app->useStoragePath('/tmp/storage');
    $app->useBootstrapPath('/tmp/bootstrap'); // For bootstrap/cache
}
<?php

/*
|--------------------------------------------------------------------------
| Return The Application Paths
|--------------------------------------------------------------------------
|
| This script returns the paths for the application, so they are available
| in the environment, even before the application is fully booted.
|
*/

if (isset($_ENV['VERCEL_ENV']) || isset($_ENV['VERCEL'])) {
    return [
        'app' => __DIR__.'/../app',
        'public' => __DIR__.'/../public',
        'base' => dirname(__DIR__),
        'storage' => '/tmp/storage', // Redirect storage to /tmp
        'bootstrap' => '/tmp/bootstrap', // Redirect bootstrap to /tmp
        'config' => __DIR__.'/../config',
        'database' => __DIR__.'/../database',
        'resources' => __DIR__.'/../resources',
        'routes' => __DIR__.'/../routes',
        'web' => __DIR__.'/../routes/web.php',
        'commands' => __DIR__.'/../routes/console.php',
    ];
}

// Fallback for local development or non-Vercel environments
return [
    'app' => __DIR__.'/../app',
    'public' => __DIR__.'/../public',
    'base' => dirname(__DIR__),
    'storage' => __DIR__.'/../storage',
    'bootstrap' => __DIR__.'/../bootstrap',
    'config' => __DIR__.'/../config',
    'database' => __DIR__.'/../database',
    'resources' => __DIR__.'/../resources',
    'routes' => __DIR__.'/../routes',
    'web' => __DIR__.'/../routes/web.php',
    'commands' => __DIR__.'/../routes/console.php',
];
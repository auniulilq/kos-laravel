<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php', // Pastikan baris API ini ada!
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Alias Middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'user'  => \App\Http\Middleware\IsUser::class,
        ]);

        // 2. Kecualikan Midtrans dari CSRF (Penting!)
        $middleware->validateCsrfTokens(except: [
            'midtrans-callback', 
            'midtrans/notification',
            'api/midtrans/notification', 
            'api/midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
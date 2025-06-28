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
        // Register custom authentication middleware
        $middleware->alias([
            'auth.customer' => \App\Http\Middleware\EnsureUserIsCustomer::class,
            'auth.admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'auth.staff' => \App\Http\Middleware\EnsureUserIsStaff::class,
            'auth.runner' => \App\Http\Middleware\EnsureUserIsRunner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

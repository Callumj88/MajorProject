<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        $middleware->web(); // Registers the web middleware stack

        // Register custom middleware aliases (optional)
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);        
    })
    ->withExceptions(function ($exceptions) {
        // 
    })
    ->create();
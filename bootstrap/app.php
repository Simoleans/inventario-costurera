<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\{RegistradorMiddleware,SalidaMiddleware};
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
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'entrada' => RegistradorMiddleware::class,
            'salida' => SalidaMiddleware::class,
            'admin_or_registrador' => \App\Http\Middleware\AdminOrRegistrador::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

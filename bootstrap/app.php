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
        // Alias de middleware personalizados
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'patient.auth' => \App\Http\Middleware\PatientAuth::class,
        ]);

        // Redirecionamentos de autenticação
        $middleware->redirectUsersTo('/dashboard');
        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
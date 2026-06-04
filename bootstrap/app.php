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
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->booting(function () {
        if (config('app.env') === 'production' || env('VERCEL')) {
            // Set view compiled path to /tmp/views on Vercel
            $viewCompiledPath = '/tmp/views';
            if (!is_dir($viewCompiledPath)) {
                mkdir($viewCompiledPath, 0755, true);
            }
            config(['view.compiled' => $viewCompiledPath]);
            
            // Set session files path to /tmp/sessions if session driver is file
            $sessionPath = '/tmp/sessions';
            if (!is_dir($sessionPath)) {
                mkdir($sessionPath, 0755, true);
            }
            config(['session.files' => $sessionPath]);
        }
    })
    ->create();

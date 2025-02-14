<?php

use App\Http\Middleware\HandlePageExpired;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(
            append: [
                App\Http\Middleware\HandleInertiaRequests::class,
                App\Http\Middleware\HandlePageExpired::class,
            ]
        );
        $middleware->alias([
            'secure.path' => App\Http\Middleware\SecurePathByRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function($schedule) {
        $schedule
            ->command('transaction:increment-age')
            ->everySecond(); // every second
            // ->daily(); // every day on 00:00
    })
    ->create();

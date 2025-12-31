<?php

use App\Http\Middleware\FacilityMode;
use App\Http\Middleware\WorkerMode;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\AddFirebaseUUID;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'worker.mode' => WorkerMode::class,
            'facility.mode' => FacilityMode::class,
            'admin' => AdminAuth::class,
            'firebase.uuid' => AddFirebaseUUID::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, $request) {

        return response()->json([
            'status' => false,
            'message' => $e->getMessage() ?: 'Unauthenticated. Please login first.',
        ], 401);
        });

    })->create();

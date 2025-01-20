<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e) {
            // Main error message
            $message = $e->getMessage();
            // Check if the exception's code is an integer
            $statusCode = is_int($e->getCode()) ? $e->getCode() : 500;

            // If the exception is an instance of NotFoundHttpException, return 404
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $statusCode = 404;
            } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                $statusCode = 422;
            } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $statusCode = 404;
            }

            return response()->json([
                "message" => $message,
                "error_code" => $statusCode
            ], $statusCode);
        });
    })->create();

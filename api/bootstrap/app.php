<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        // web: __DIR__.'/../routes/web.php',
        // commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {
            if ($e instanceof AuthenticationException) return response()->json(['error' => 'Unauthenticated.'], 401);
            if ($e instanceof AuthorizationException) return response()->json(['error' => 'Forbidden.'], 403);
            if ($e instanceof ModelNotFoundException) return response()->json(['error' => 'Model not found.'], 404);
            if ($e instanceof NotFoundHttpException) return response()->json(['error' => 'Not found.'], 404);
            if ($e instanceof MethodNotAllowedHttpException) return response()->json(['error' => 'Not allowed.'], 405);

            if ($e instanceof ValidationException) return response()->json(['message' => 'Validation error.', 'errors' => $e->errors()], 422);

            return response()->json(['message' => 'Server error.'], 500);
        });
    })->create();

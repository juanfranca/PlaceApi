<?php

use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $e, $request) {
            $trait = new class {
                use ApiResponse;
            };

            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return $trait->errorResponse('Place not found.', 404);
            }
            return $trait->errorResponse($e->getMessage(), ($e->getCode() >= 100 && $e->getCode() < 600) ? $e->getCode() : 500);
        });
    })->create();

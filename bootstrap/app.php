<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->render(function (AuthorizationException|AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'mensaje' => 'Usted no tiene permisos para acceder a esta ruta o realizar esta acción.',
                ], 403);
            }
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            $message = "Recurso no encontrado";
            $exception_type = $e->getMessage();
            if ($exception_type instanceof ModelNotFoundException) {
                $model = class_basename($exception_type->getModel());
                $messages = [];
                $message = $messages[$model] ?? "Recurso no encontrado";
            }
            return response()->json([
                'message' => $message
            ], 404);
        });
    })->create();

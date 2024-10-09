<?php

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Client\ConnectionException as HttpConnectionException;
use Illuminate\Validation\ValidationException;
use Predis\Connection\ConnectionException as RedisConnectionException;
use Propaganistas\LaravelPhone\Exceptions\NumberParseException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
    })

    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'message'=>$e->getMessage(),
                'data'=>$e->getTrace(),
                'isSuccess'=>false
            ],405);
        });

        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'message'=>$e->getMessage(),
                'data'=>$e->getTrace(),
                'isSuccess'=>false
            ],404);
        });

        $exceptions->renderable(function (AccessDeniedHttpException $e, $request) {
            return response()->json([
                'message'=>$e->getMessage(),
                'data'=>$e->getTrace(),
                'isSuccess'=>false
            ],403);
        });

        $exceptions->renderable(function (
            BindingResolutionException  $e, $request) {
            return response()->json([
                'message'=>$e->getMessage(),
                'data'=>$e->getTrace(),
                'isSuccess'=>false
            ],500);
        });

        $exceptions->renderable(function (
            HttpConnectionException $e, $request) {
            return response()->json([
                'message'=>$e->getMessage(),
                'data'=>$e->getTrace(),
                'isSuccess'=>false
            ],500);
        });


        $exceptions->renderable(function (ValidationException $e, $request) {
            return response()->json([
                'message'=>$e->getMessage(),
                'data'=>$e->getTrace(),
                'isSuccess'=>false
            ],422);
        });

        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'message'=>$e->getMessage(),
                'data'=>[],
                'isSuccess'=>false
            ],401);
        });

        $exceptions->render(function (Throwable $e) {
            return response()->json([
                'message'=>$e->getMessage(),
                'data'=>$e->getTrace(),
                'isSuccess'=>false
            ],500);
        });
    })
    ->create();

<?php

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Configuration;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: [
            __DIR__ . '/../routes/guarded.php',
            __DIR__ . '/../routes/auth.php',
        ]
    )
    ->withMiddleware(function (Configuration\Middleware $middleware): void {
        // $middleware->api(append: ['throttle:api']);
    })
    ->withExceptions(function (Configuration\Exceptions $exceptions): void {
        // convert all errors to JSON
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // format error message returned by the response
        $exceptions->respond(function (Response $response) {
            return response()->json([
                'message' => Response::$statusTexts[$response->getStatusCode()] ?? 'An unexpected error occurred.',
                'status'  => $response->getStatusCode(),
            ], $response->getStatusCode());
        });
    })->create();
